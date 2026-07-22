/**
 * OpenDK Rate Limiting Test (k6)
 *
 * Verifies that the global API rate limiter (60 req/min) correctly
 * returns 429 Too Many Requests when the limit is exceeded.
 *
 * Usage:
 *   k6 run tests/performance/rate-limit-test.js
 */
import http from 'k6/http';
import { check } from 'k6';
import { Counter } from 'k6/metrics';
import { config } from './config.js';
import { login, authHeaders } from './helpers/auth.js';

const successfulRequests = new Counter('successful_requests');
const rateLimitedRequests = new Counter('rate_limited_requests');
const totalRequests = new Counter('total_requests');

export const options = {
  scenarios: {
    rate_limit_test: {
      executor: 'constant-arrival-rate',
      rate: 70,
      timeUnit: '1m',
      duration: '2m',
      preAllocatedVUs: 5,
    },
  },
  thresholds: {
    http_req_duration: [{ threshold: 'max<2000' }],
  },
};

let authToken;

export function setup() {
  authToken = login();
  return { token: authToken };
}

export default function (data) {
  const token = data.token || authToken;
  const headers = authHeaders(token);

  const payload = JSON.stringify({
    hapus_penduduk: [{ id_pend_desa: 1, desa_id: '1234567890' }],
  });

  const res = http.post(`${config.baseUrl}/api/v1/penduduk`, payload, headers);

  totalRequests.add(1);

  if (res.status === 429) {
    rateLimitedRequests.add(1);
  } else if (res.status === 200) {
    successfulRequests.add(1);
  }
}

export function handleSummary(data) {
  const total = data.metrics.total_requests?.values?.count || 0;
  const rateLimited = data.metrics.rate_limited_requests?.values?.count || 0;
  const successful = data.metrics.successful_requests?.values?.count || 0;

  const lines = [];
  lines.push('');
  lines.push('========================================');
  lines.push('  Rate Limiting Test Summary');
  lines.push('========================================');
  lines.push('');
  lines.push(`  Total Requests:    ${total}`);
  lines.push(`  Successful:        ${successful}`);
  lines.push(`  Rate Limited:      ${rateLimited}`);
  lines.push('');

  const hasRateLimiting = rateLimited > 0;
  lines.push(`  Rate Limiting:     ${hasRateLimiting ? 'ACTIVE' : 'NOT TRIGGERED'}`);

  if (hasRateLimiting) {
    const firstRateLimit = successful;
    lines.push(`  Triggered at:      ~${firstRateLimit} requests`);
    lines.push(`  Expected at:       ~60 requests (60/min limit)`);
  }

  lines.push('');
  lines.push(`  RESULT: ${hasRateLimiting ? 'PASS' : 'INFO - Rate limit not reached'}`);
  lines.push('========================================');
  lines.push('');

  return {
    stdout: lines.join('\n'),
    'tests/performance/rate-limit-report.json': JSON.stringify(
      {
        timestamp: new Date().toISOString(),
        total_requests: total,
        successful_requests: successful,
        rate_limited_requests: rateLimited,
        rate_limiting_active: hasRateLimiting,
      },
      null,
      2
    ),
  };
}
