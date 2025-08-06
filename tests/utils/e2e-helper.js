const { expect } = require('@playwright/test');

class E2ETestHelper {
  static async waitForPageReady(page, timeout = 10000) {
    await page.waitForLoadState('networkidle', { timeout });
  }

  static async fillFormField(page, selector, value, options = {}) {
    const element = page.locator(selector);
    await element.waitFor({ state: 'visible', timeout: options.timeout || 10000 });
    await element.fill(value);
  }

  static async clickElement(page, selector, options = {}) {
    const element = page.locator(selector);
    await element.waitFor({ state: 'visible', timeout: options.timeout || 10000 });
    await element.click();
  }

  static async takeScreenshotOnFailure(page, testInfo) {
    if (testInfo.status !== testInfo.expectedStatus) {
      const screenshot = await page.screenshot();
      await testInfo.attach('screenshot', { body: screenshot, contentType: 'image/png' });
    }
  }

  static setupPageLogging(page) {
    page.on('console', msg => {
      if (msg.type() === 'error') {
        console.log(`PAGE ERROR: ${msg.text()}`);
      }
    });

    page.on('pageerror', error => {
      console.log(`PAGE CRASH: ${error.message}`);
    });

    page.on('requestfailed', request => {
      console.log(`REQUEST FAILED: ${request.url()} - ${request.failure()?.errorText}`);
    });
  }

  static async waitForElement(page, selector, options = {}) {
    const timeout = options.timeout || 10000;
    const state = options.state || 'visible';

    return await page.waitForSelector(selector, {
      state,
      timeout
    });
  }

  static async expectElementToContainText(page, selector, text, options = {}) {
    const element = page.locator(selector);
    await element.waitFor({ state: 'visible', timeout: options.timeout || 10000 });
    await expect(element).toContainText(text);
  }

  static async getPageTitle(page) {
    return await page.title();
  }

  static async getCurrentUrl(page) {
    return page.url();
  }

  static async isAuthenticated(page) {
    // Check if we're not on login page
    const currentUrl = await this.getCurrentUrl(page);
    return !currentUrl.includes('/login');
  }

  static async hasUserMenu(page) {
    // Check for common user menu elements
    const userMenuSelectors = [
      '.user-menu',
      '.navbar-nav .dropdown',
      '[class*="user"]',
      '.dropdown-toggle'
    ];

    for (const selector of userMenuSelectors) {
      const count = await page.locator(selector).count();
      if (count > 0) {
        return true;
      }
    }
    return false;
  }

  static async navigateAndWait(page, url) {
    await page.goto(url);
    await this.waitForPageReady(page);
  }
}

module.exports = E2ETestHelper;
