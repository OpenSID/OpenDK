const { test, expect, chromium } = require('@playwright/test');
const E2ETestHelper = require('../utils/e2e-helper');

test.describe('Homepage Tests', () => {  

  // Test ini tidak menggunakan authentication state karena homepage adalah public
  test.use({ storageState: { cookies: [], origins: [] } });

  test.beforeEach(async ({ page }) => {
    page.setDefaultTimeout(30000);
    page.setDefaultNavigationTimeout(30000);
    E2ETestHelper.setupPageLogging(page);
  });

  // run test

  test('should display homepage when website is enabled', async ({ page }) => {

    await page.goto('/');
    await E2ETestHelper.waitForPageReady(page);

    // Check page title
    const title = await E2ETestHelper.getPageTitle(page);
    expect(title).toBeTruthy();
    expect(title).not.toBe('404');

    // Check for main homepage elements
    await expect(page.locator('body')).toBeVisible();

    // Check for navigation menu (biasanya ada di homepage)
    const hasNavigation = await page.locator('nav, .navbar, .menu').count() > 0;
    expect(hasNavigation).toBeTruthy();

    // Check for main content area
    const hasMainContent = await page.locator('main, .content, .container').count() > 0;
    expect(hasMainContent).toBeTruthy();

    // Verify page is not an error page
    const pageContent = await page.textContent('body');
    expect(pageContent).not.toContain('404');
    expect(pageContent).not.toContain('Not Found');
    expect(pageContent).not.toContain('Error');
  });

  test('should have responsive design', async ({ page }) => {
    await page.goto('/');
    await E2ETestHelper.waitForPageReady(page);

    // Test mobile viewport
    await page.setViewportSize({ width: 375, height: 667 });
    await page.waitForTimeout(1000);

    // Check if page is still functional on mobile
    const bodyVisible = await page.locator('body').isVisible();
    expect(bodyVisible).toBeTruthy();

    // Test tablet viewport
    await page.setViewportSize({ width: 768, height: 1024 });
    await page.waitForTimeout(1000);

    // Check if page is still functional on tablet
    const bodyVisibleTablet = await page.locator('body').isVisible();
    expect(bodyVisibleTablet).toBeTruthy();

    // Reset to desktop
    await page.setViewportSize({ width: 1280, height: 720 });
  });
});
