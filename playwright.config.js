const { defineConfig, devices } = require('@playwright/test');
const configLoader = require('./tests/config-loader');

module.exports = defineConfig({
  testDir: './tests/e2e',
  fullyParallel: false,
  forbidOnly: !!process.env.CI,
  retries: process.env.CI ? 2 : 1,
  workers: process.env.CI ? 1 : 2,

  // Global setup untuk authentication
  globalSetup: require.resolve('./tests/global-setup'),  reporter: [
    ['html'],
    ['json', { outputFile: 'test-results/results.json' }],
    ['list']
  ],

  use: {
    baseURL: configLoader.get('app.baseURL'),
    trace: configLoader.get('media.trace'),
    screenshot: configLoader.get('media.screenshot'),
    video: configLoader.get('media.video'),
    actionTimeout: configLoader.get('app.timeout'),
    navigationTimeout: configLoader.get('app.timeout'),

    // Additional settings for better reliability
    ignoreHTTPSErrors: true,
    bypassCSP: true,

    // Set viewport
    viewport: { width: 1280, height: 720 },

    // User agent
    userAgent: 'Mozilla/5.0 (Windows NT 10.0; Win64; x64) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/91.0.4472.124 Safari/537.36'
  },

  projects: [
    // Project untuk test yang memerlukan authentication
    {
      name: 'authenticated',
      use: {
        ...devices['Desktop Chrome'],
        // Use saved authentication state
        storageState: './test-results/storage-state/auth.json'
      },
      //testIgnore: ['**/login.spec.js', '**/homepage.spec.js', '**/homepage-link.spec.js'] // Skip login tests untuk authenticated project
    },
  ],

  webServer: {
    command: `APP_ENV=testing ${configLoader.get('app.phpVersion')} artisan serve --env=testing`,
    url: configLoader.get('app.baseURL'),
    reuseExistingServer: !process.env.CI,
    timeout: 120 * 1000,
    env: {
      APP_ENV: 'testing',     
    }
  },
});
