const fs = require('fs');
const path = require('path');

class E2EConfigLoader {
  constructor() {
    this.config = this.loadConfiguration();
  }

  loadConfiguration() {
    let config = this.getDefaultConfig();

    // Load environment file
    try {
      const envPath = path.join(__dirname, '..', '.env.e2e');
      if (fs.existsSync(envPath)) {
        this.loadEnvFile(envPath);
      }
    } catch (error) {
      console.warn('Could not load .env.e2e:', error.message);
    }

    // Override with environment variables
    config = this.overrideWithEnv(config);

    return config;
  }

  getDefaultConfig() {
    return {
      auth: {
        email: 'test@test.com', // Test user yang dibuat
        password: 'password' // Password yang diketahui
      },
      app: {
        baseURL: 'http://localhost:8000',
        timeout: 30000,
        phpVersion: 'php' // Default PHP executable
      },
      database: {
        connection: 'mysql',
        host: 'localhost',
        port: 3306,
        database: 'opendesa_test',
        username: 'root',
        password: 'secret'
      },
      testData: {
      },
      browser: {
        headless: true,
        slowMo: 0
      },
      media: {
        screenshot: 'only-on-failure',
        video: 'retain-on-failure',
        trace: 'on-first-retry'
      }
    };
  }

  loadEnvFile(filePath) {
    const envContent = fs.readFileSync(filePath, 'utf8');
    envContent.split('\n').forEach(line => {
      const [key, ...valueParts] = line.split('=');
      if (key && valueParts.length > 0) {
        const value = valueParts.join('=').trim();
        process.env[key.trim()] = value;
      }
    });
  }

  overrideWithEnv(config) {
    // Auth overrides
    if (process.env.E2E_ADMIN_EMAIL) {
      config.auth.email = process.env.E2E_ADMIN_EMAIL;
    }
    if (process.env.E2E_ADMIN_PASSWORD) {
      config.auth.password = process.env.E2E_ADMIN_PASSWORD;
    }

    // App overrides
    if (process.env.E2E_APP_URL) {
      config.app.baseURL = process.env.E2E_APP_URL;
    }
    if (process.env.E2E_TIMEOUT) {
      config.app.timeout = parseInt(process.env.E2E_TIMEOUT);
    }
    if (process.env.E2E_PHP_VERSION) {
      config.app.phpVersion = process.env.E2E_PHP_VERSION;
    }

    // Database overrides
    if (process.env.E2E_DB_CONNECTION) {
      config.database.connection = process.env.E2E_DB_CONNECTION;
    }
    if (process.env.E2E_DB_HOST) {
      config.database.host = process.env.E2E_DB_HOST;
    }
    if (process.env.E2E_DB_PORT) {
      config.database.port = parseInt(process.env.E2E_DB_PORT);
    }
    if (process.env.E2E_DB_DATABASE) {
      config.database.database = process.env.E2E_DB_DATABASE;
    }
    if (process.env.E2E_DB_USERNAME) {
      config.database.username = process.env.E2E_DB_USERNAME;
    }
    if (process.env.E2E_DB_PASSWORD) {
      config.database.password = process.env.E2E_DB_PASSWORD;
    }

    // Browser overrides
    if (process.env.E2E_HEADLESS) {
      config.browser.headless = process.env.E2E_HEADLESS === 'true';
    }
    if (process.env.E2E_SLOWMO) {
      config.browser.slowMo = parseInt(process.env.E2E_SLOWMO);
    }

    // Media overrides
    if (process.env.E2E_SCREENSHOT) {
      config.media.screenshot = process.env.E2E_SCREENSHOT;
    }
    if (process.env.E2E_VIDEO) {
      config.media.video = process.env.E2E_VIDEO;
    }
    if (process.env.E2E_TRACE) {
      config.media.trace = process.env.E2E_TRACE;
    }

    return config;
  }

  mergeConfig(target, source) {
    const merged = { ...target };

    for (const key in source) {
      if (source[key] && typeof source[key] === 'object' && !Array.isArray(source[key])) {
        merged[key] = this.mergeConfig(merged[key] || {}, source[key]);
      } else {
        merged[key] = source[key];
      }
    }

    return merged;
  }

  get(path = '') {
    if (!path) return this.config;

    return path.split('.').reduce((obj, key) => obj?.[key], this.config);
  }
}

// Singleton instance
const configLoader = new E2EConfigLoader();
module.exports = configLoader;
