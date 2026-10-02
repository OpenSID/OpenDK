/**
 * OpenDK API Load Test (k6)
 *
 * Runs load tests against core OpenDK sync endpoints.
 * Validates p95 latency < 500ms and error rate < 0.1%.
 *
 * Usage:
 *   k6 run tests/performance/load-test.js
 *   K6_BASE_URL=http://127.0.0.1:8000 k6 run tests/performance/load-test.js
 *   K6_P95_THRESHOLD_MS=300 K6_VUS=20 k6 run tests/performance/load-test.js
 */
import http from 'k6/http';
import { check, group, sleep } from 'k6';
import { Counter, Rate, Trend } from 'k6/metrics';
import { config, options } from './config.js';
import { login, authHeaders } from './helpers/auth.js';

export { options };

const loginSuccess = new Counter('login_success');
const loginFailure = new Counter('login_failure');
const syncRequests = new Counter('sync_requests');
const syncErrors = new Counter('sync_errors');
const syncDuration = new Trend('sync_duration', true);
const rateLimited = new Counter('rate_limited');

let authToken;

export function setup() {
  authToken = login();
  return { token: authToken };
}

export default function (data) {
  const token = data.token || authToken;
  const headers = authHeaders(token);

  group('POST /api/v1/auth/login', () => {
    const payload = JSON.stringify({
      email: config.auth.email,
      password: config.auth.password,
    });

    const res = http.post(`${config.baseUrl}/api/v1/auth/login`, payload, {
      headers: { 'Content-Type': 'application/json' },
    });

    const success = check(res, {
      'login status is 200': (r) => r.status === 200,
    });

    if (success) {
      loginSuccess.add(1);
    } else {
      loginFailure.add(1);
    }
  });

  sleep(0.5);

  group('POST /api/v1/penduduk', () => {
    const payload = JSON.stringify({
      hapus_penduduk: [
        {
          id_pend_desa: Math.floor(Math.random() * 100000),
          desa_id: '1234567890',
        },
      ],
    });

    const res = http.post(`${config.baseUrl}/api/v1/penduduk`, payload, headers);

    syncDuration.add(res.timings.duration);
    syncRequests.add(1);

    const success = check(res, {
      'penduduk sync status is 200': (r) => r.status === 200,
    });

    if (!success) {
      syncErrors.add(1);
      if (res.status === 429) {
        rateLimited.add(1);
      }
    }
  });

  sleep(0.3);

  group('POST /api/v1/identitas-desa', () => {
    const payload = JSON.stringify({
      kode_desa: '1234567890',
      sebutan_desa: 'Load Test Desa',
      website: 'https://loadtest.desa.id',
      path: '[]',
    });

    const res = http.post(`${config.baseUrl}/api/v1/identitas-desa`, payload, headers);

    syncDuration.add(res.timings.duration);
    syncRequests.add(1);

    const success = check(res, {
      'identitas-desa status is 200': (r) => r.status === 200,
    });

    if (!success) {
      syncErrors.add(1);
      if (res.status === 429) {
        rateLimited.add(1);
      }
    }
  });

  sleep(0.3);

  group('POST /api/v1/pesan', () => {
    const payload = JSON.stringify({
      kode_desa: '1234567890',
      subjek: 'Test Pesan Load Test',
      pesan: 'Ini adalah pesan dari load test k6',
    });

    const res = http.post(`${config.baseUrl}/api/v1/pesan`, payload, headers);

    syncDuration.add(res.timings.duration);
    syncRequests.add(1);

    const success = check(res, {
      'pesan status is 200 or 422': (r) => r.status === 200 || r.status === 422,
    });

    if (!success) {
      syncErrors.add(1);
      if (res.status === 429) {
        rateLimited.add(1);
      }
    }
  });

  sleep(0.5);

  group('GET /api/v1/surat', () => {
    const res = http.get(`${config.baseUrl}/api/v1/surat`, headers);

    syncDuration.add(res.timings.duration);
    syncRequests.add(1);

    const success = check(res, {
      'surat list status is 200': (r) => r.status === 200,
    });

    if (!success) {
      syncErrors.add(1);
      if (res.status === 429) {
        rateLimited.add(1);
      }
    }
  });
}

export function teardown(data) {
  // Cleanup if needed
}

export function handleSummary(data) {
  const summary = {
    timestamp: new Date().toISOString(),
    metrics: {
      http_req_duration_p95:
        data.metrics.http_req_duration?.values?.['p(95)'] || 0,
      http_req_failed_rate:
        data.metrics.http_req_failed?.values?.rate || 0,
      sync_duration_p95:
        data.metrics.sync_duration?.values?.['p(95)'] || 0,
      total_requests:
        data.metrics.http_reqs?.values?.count || 0,
      login_success: data.metrics.login_success?.values?.count || 0,
      login_failure: data.metrics.login_failure?.values?.count || 0,
      sync_requests: data.metrics.sync_requests?.values?.count || 0,
      sync_errors: data.metrics.sync_errors?.values?.count || 0,
      rate_limited: data.metrics.rate_limited?.values?.count || 0,
    },
    thresholds: {
      p95_pass:
        (data.metrics.http_req_duration?.values?.['p(95)'] || 0) <
        config.thresholds.p95,
      error_rate_pass:
        (data.metrics.http_req_failed?.values?.rate || 0) <
        config.thresholds.errorRate,
    },
  };

  const report = JSON.stringify(summary, null, 2);

  return {
    stdout: textSummary(data, { indent: ' ', enableColors: true }),
    'tests/performance/report.json': report,
  };
}

function textSummary(data, options) {
  const lines = [];
  lines.push('');
  lines.push('========================================');
  lines.push('  OpenDK Performance Test Summary');
  lines.push('========================================');
  lines.push('');

  const p95 = data.metrics.http_req_duration?.values?.['p(95)'] || 0;
  const errorRate = data.metrics.http_req_failed?.values?.rate || 0;
  const totalReqs = data.metrics.http_reqs?.values?.count || 0;

  lines.push(`  Total Requests:    ${totalReqs}`);
  lines.push(`  p95 Latency:       ${p95.toFixed(2)} ms (threshold: ${config.thresholds.p95} ms)`);
  lines.push(`  Error Rate:        ${(errorRate * 100).toFixed(3)}% (threshold: ${(config.thresholds.errorRate * 100).toFixed(3)}%)`);
  lines.push(`  Sync Requests:     ${data.metrics.sync_requests?.values?.count || 0}`);
  lines.push(`  Sync Errors:       ${data.metrics.sync_errors?.values?.count || 0}`);
  lines.push(`  Rate Limited:      ${data.metrics.rate_limited?.values?.count || 0}`);
  lines.push('');

  const p95Pass = p95 < config.thresholds.p95;
  const errorPass = errorRate < config.thresholds.errorRate;

  lines.push(`  p95 Threshold:     ${p95Pass ? 'PASS' : 'FAIL'}`);
  lines.push(`  Error Rate Check:  ${errorPass ? 'PASS' : 'FAIL'}`);
  lines.push('');

  if (!p95Pass || !errorPass) {
    lines.push('  RESULT: FAIL - Thresholds exceeded');
  } else {
    lines.push('  RESULT: PASS');
  }

  lines.push('========================================');
  lines.push('');

  return lines.join('\n');
}
