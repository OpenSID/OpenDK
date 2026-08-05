/**
 * k6 Authentication Helper
 *
 * Handles JWT login and token management for k6 load tests.
 */
import http from 'k6/http';
import { check } from 'k6';
import { config } from '../config.js';

/**
 * Login and return JWT access token.
 */
export function login() {
  const payload = JSON.stringify({
    email: config.auth.email,
    password: config.auth.password,
  });

  const params = {
    headers: { 'Content-Type': 'application/json' },
  };

  const res = http.post(`${config.baseUrl}/api/v1/auth/login`, payload, params);

  check(res, {
    'login status is 200': (r) => r.status === 200,
    'login returns access_token': (r) => {
      try {
        const body = JSON.parse(r.body);
        return body.access_token !== undefined;
      } catch {
        return false;
      }
    },
  });

  if (res.status !== 200) {
    throw new Error(`Login failed: status ${res.status}`);
  }

  const body = JSON.parse(res.body);
  return body.access_token;
}

/**
 * Get headers with JWT authorization.
 */
export function authHeaders(token) {
  return {
    headers: {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`,
    },
  };
}
