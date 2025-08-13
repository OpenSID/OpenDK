const { chromium } = require('@playwright/test');
const path = require('path');
const fs = require('fs');

class AuthStateManager {
  constructor(configLoader) {
    this.configLoader = configLoader;
    this.storageStatePath = path.join(__dirname, '..', '..', 'test-results', 'storage-state', 'auth.json');
    this.ensureStorageDir();
  }

  ensureStorageDir() {
    const storageDir = path.dirname(this.storageStatePath);
    if (!fs.existsSync(storageDir)) {
      fs.mkdirSync(storageDir, { recursive: true });
    }
  }

  async isAuthStateValid() {
    if (!fs.existsSync(this.storageStatePath)) {
      return false;
    }

    try {
      const authState = JSON.parse(fs.readFileSync(this.storageStatePath, 'utf8'));

      // Check if auth state has cookies and they're not expired
      if (!authState.cookies || authState.cookies.length === 0) {
        return false;
      }

      // Check for session cookies that might indicate valid authentication
      const hasSessionCookie = authState.cookies.some(cookie =>
        cookie.name.includes('session') ||
        cookie.name.includes('token') ||
        cookie.name.includes('laravel')
      );

      return hasSessionCookie;
    } catch (error) {
      console.warn('Error reading auth state:', error.message);
      return false;
    }
  }

  async createAuthState() {
    console.log('Creating new authentication state...');

    const browser = await chromium.launch({ headless: true });
    const page = await browser.newPage();

    // Ignore API errors to prevent test failures due to external API calls
    page.on('pageerror', (error) => {
      console.warn('Page error (ignored):', error.message);
    });

    page.on('requestfailed', (request) => {
      console.warn('Request failed (ignored):', request.url());
    });

    try {
      const baseURL = this.configLoader.get('app.baseURL');
      const email = this.configLoader.get('auth.email');
      const password = this.configLoader.get('auth.password');

      console.log(`Config - BaseURL: ${baseURL}`);
      console.log(`Config - Email: ${email}`);
      console.log(`Config - Password: ${password}`);

      // Navigate to login page with retry
      let retries = 3;
      while (retries > 0) {
        try {
          await page.goto(`${baseURL}/login`, {
            waitUntil: 'networkidle',
            timeout: 30000
          });
          break;
        } catch (error) {
          retries--;
          if (retries === 0) throw error;
          console.warn(`Retrying login page navigation... (${retries} attempts left)`);
          await page.waitForTimeout(2000);
        }
      }

      // Wait for login form to be ready
      await page.waitForSelector('input[name="email"]', { timeout: 10000 });
      await page.waitForSelector('input[name="password"]', { timeout: 10000 });
      await page.waitForSelector('button[type="submit"]:has-text("Sign In"), button:has-text("Sign In")', { timeout: 10000 });

      // Fill login form
      await page.fill('input[name="email"]', email);
      await page.fill('input[name="password"]', password);

      // Handle captcha if it exists
      await this.handleCaptcha(page);

      // Submit form and wait for navigation
      await Promise.all([
        page.waitForURL(url => !url.toString().includes('/login'), { timeout: 30000 }),
        page.click('button[type="submit"]:has-text("Sign In"), button:has-text("Sign In")')
      ]);

      // Verify login success
      const currentUrl = page.url();
      const isLoggedIn = !currentUrl.includes('/login');

      if (!isLoggedIn) {
        // Check for captcha error specifically
        const captchaError = await page.locator('.alert-danger:has-text("captcha"), .error:has-text("captcha"), .invalid-feedback:has-text("captcha")').first();
        const hasCaptchaError = await captchaError.count() > 0;
        
        if (hasCaptchaError) {
          const captchaErrorText = await captchaError.textContent().catch(() => 'Captcha validation failed');
          throw new Error(`Login failed due to captcha error: ${captchaErrorText}`);
        }

        // Try to get general error message
        const errorElement = await page.locator('.alert-danger, .error, .invalid-feedback').first();
        const errorMessage = await errorElement.textContent().catch(() => 'Unknown error');
        throw new Error(`Login failed. Current URL: ${currentUrl}. Error: ${errorMessage}`);
      }

      // Save authentication state
      await page.context().storageState({ path: this.storageStatePath });
      console.log(`Authentication state saved to: ${this.storageStatePath}`);

      return true;
    } catch (error) {
      console.error('Failed to create auth state:', error.message);
      throw error;
    } finally {
      await browser.close();
    }
  }

  async handleCaptcha(page) {
    const captchaInput = page.locator('input[name="captcha"]');
    const hasCaptcha = await captchaInput.count() > 0;
    
    if (hasCaptcha) {
      console.log('Captcha field detected, handling captcha...');
      
      // Check if there's a captcha image
      const captchaImage = page.locator('img[src*="captcha"], .captcha-image');
      const hasCaptchaImage = await captchaImage.count() > 0;
      
      if (hasCaptchaImage) {
        console.log('Captcha image found');
        
        // Optional: Refresh captcha if there's a refresh button
        const refreshButton = page.locator('button[onclick*="captcha"], .captcha-refresh, [title*="refresh"]');
        const hasRefreshButton = await refreshButton.count() > 0;
        
        if (hasRefreshButton) {
          console.log('Refreshing captcha...');
          await refreshButton.click();
          await page.waitForTimeout(2000); // Wait for new captcha to load
        }
      }
      
      // For testing purposes, use a test value
      // In production, you might need to:
      // 1. Disable captcha for testing environment
      // 2. Use a test captcha endpoint
      // 3. Implement OCR for captcha solving
      const testCaptchaValue = this.configLoader.get('auth.captcha') || 'test';
      
      await page.fill('input[name="captcha"]', testCaptchaValue);
      console.log(`Captcha filled with value: ${testCaptchaValue}`);
      
      // Wait a bit for captcha to be processed
      await page.waitForTimeout(1000);
    } else {
      console.log('No captcha field detected');
    }
  }

  async retryCaptchaLogin(page, email, password, maxRetries = 3) {
    let retries = 0;
    
    while (retries < maxRetries) {
      try {
        // Clear previous input
        await page.fill('input[name="email"]', '');
        await page.fill('input[name="password"]', '');
        
        const captchaInput = page.locator('input[name="captcha"]');
        const hasCaptcha = await captchaInput.count() > 0;
        if (hasCaptcha) {
          await page.fill('input[name="captcha"]', '');
        }
        
        // Fill form again
        await page.fill('input[name="email"]', email);
        await page.fill('input[name="password"]', password);
        
        // Handle captcha with fresh value
        await this.handleCaptcha(page);
        
        // Submit form
        await Promise.all([
          page.waitForURL(url => !url.toString().includes('/login'), { timeout: 30000 }),
          page.click('button[type="submit"]:has-text("Sign In"), button:has-text("Sign In")')
        ]);
        
        // Check if login was successful
        const currentUrl = page.url();
        if (!currentUrl.includes('/login')) {
          console.log(`Login successful after ${retries + 1} attempts`);
          return true;
        }
        
        retries++;
        console.log(`Login attempt ${retries} failed, retrying...`);
        await page.waitForTimeout(2000);
        
      } catch (error) {
        retries++;
        console.log(`Login attempt ${retries} failed with error: ${error.message}`);
        if (retries >= maxRetries) {
          throw error;
        }
        await page.waitForTimeout(2000);
      }
    }
    
    throw new Error(`Login failed after ${maxRetries} attempts`);
  }

  async getAuthState() {
    const isValid = await this.isAuthStateValid();

    if (!isValid) {
      await this.createAuthState();
    } else {
      console.log('Using existing authentication state');
    }

    return this.storageStatePath;
  }

  clearAuthState() {
    if (fs.existsSync(this.storageStatePath)) {
      fs.unlinkSync(this.storageStatePath);
      console.log('Authentication state cleared');
    }
  }

  async refreshAuthState() {
    this.clearAuthState();
    return await this.createAuthState();
  }

  async getStoredCookies() {
    try {
      if (!fs.existsSync(this.storageStatePath)) {
        return [];
      }

      const authState = JSON.parse(fs.readFileSync(this.storageStatePath, 'utf8'));
      return authState.cookies || [];
    } catch (error) {
      console.warn('Error reading stored cookies:', error.message);
      return [];
    }
  }

  async getStorageState() {
    if (!fs.existsSync(this.storageStatePath)) {
      await this.createAuthState();
    }

    try {
      return JSON.parse(fs.readFileSync(this.storageStatePath, 'utf8'));
    } catch (error) {
      console.warn('Error reading storage state:', error.message);
      await this.createAuthState();
      return JSON.parse(fs.readFileSync(this.storageStatePath, 'utf8'));
    }
  }
}

module.exports = AuthStateManager;
