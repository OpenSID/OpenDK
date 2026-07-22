/**
 * k6 Performance Test Configuration
 *
 * Thresholds and scenario settings for OpenDK API load testing.
 * Environment variables can override defaults:
 *   K6_BASE_URL, K6_P95_THRESHOLD_MS, K6_ERROR_RATE_THRESHOLD
 */

const p95Threshold = parseInt(__ENV.K6_P95_THRESHOLD_MS || '500', 10);
const errorRateThreshold = parseFloat(__ENV.K6_ERROR_RATE_THRESHOLD || '0.001');
const vus = parseInt(__ENV.K6_VUS || '10', 10);
const duration = __ENV.K6_DURATION || '30s';

export const config = {
  baseUrl: __ENV.K6_BASE_URL || 'http://127.0.0.1:8000',
  auth: {
    email: __ENV.K6_ADMIN_EMAIL || 'admin@mail.com',
    password: __ENV.K6_ADMIN_PASSWORD || 'Admin123!',
  },
  thresholds: {
    p95: p95Threshold,
    errorRate: errorRateThreshold,
  },
  load: {
    vus,
    duration,
  },
};

export const options = {
  thresholds: {
    http_req_duration: [
      {
        threshold: `p(95)<${config.thresholds.p95}`,
        abortOnFail: true,
        delayAbortEval: '10s',
      },
    ],
    http_req_failed: [
      {
        threshold: `rate<${config.thresholds.errorRate}`,
        abortOnFail: true,
        delayAbortEval: '10s',
      },
    ],
  },
  stages: [
    { duration: '5s', target: Math.ceil(config.load.vus / 2) },
    { duration: `${config.load.duration}`, target: config.load.vus },
    { duration: '5s', target: 0 },
  ],
};
