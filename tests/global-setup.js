const configLoader = require('./config-loader');
const AuthStateManager = require('./utils/auth-state-manager');

async function globalSetup() {
  console.log('Starting global setup for E2E tests...');

  try {
    // Initialize auth state manager
    const authManager = new AuthStateManager(configLoader);

    // Get or create authentication state
    await authManager.getAuthState();

    console.log('Global setup completed successfully');
  } catch (error) {
    console.error('Global setup failed:', error.message);
    throw error;
  }
}

module.exports = globalSetup;
