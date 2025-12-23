const { test, expect } = require('@playwright/test');
const configLoader = require('../config-loader');
const E2ETestHelper = require('../utils/e2e-helper');
const AuthStateManager = require('../utils/auth-state-manager');

test.describe('Dashboard Page Tests', () => {
  // Test ini menggunakan authentication state karena dashboard memerlukan login
  //test.use({ storageState: './test-results/storage-state/auth.json' });

  test.beforeEach(async ({ page }) => {
    page.setDefaultTimeout(30000);
    page.setDefaultNavigationTimeout(30000);
    E2ETestHelper.setupPageLogging(page);
    
    // Navigate to dashboard
    await page.goto('/dashboard');
    await page.waitForLoadState('networkidle');
  });

  test('should display dashboard page correctly', async ({ page }) => {
    // Verify page title and heading
    await expect(page).toHaveTitle(/Dashboard/i);
    await expect(page.locator('h1, .page-title, .content-header h1')).toContainText(/dashboard/i);
    
    // Verify dashboard text is present
    await expect(page.locator('body')).toContainText(/dashboard/i);
    
    console.log('✅ Dashboard page title and heading verified');
  });

  test('Set accessibility widget setting enabled', async ({ page }) => {
    await page.goto('/setting/aplikasi');

    // Expect a title "to contain" a substring.
    await expect(page).toHaveTitle(/Pengaturan Aplikasi/);
    // Check Dukungan Disabilitas exists
    const settingsTable = page.locator('table#user-table');
    const settingsEntries = settingsTable.getByRole('row');
    const accessibilitySetting = settingsEntries.filter( {hasText: /dukungan disabilitas/i} )
    const accSettingText = await accessibilitySetting.innerText();
    console.log('✅ Row text: ', accSettingText);

    const settingStatus = accessibilitySetting.getByRole('cell').nth(1);
    await expect(settingStatus).toHaveText('Tidak Aktif');
    console.log('✅ Setting is disabled: ', await settingStatus.textContent());
    const accessibilityButton = accessibilitySetting.getByRole('button');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        // E2ETestHelper.clickElement(page, accessibilityButton)
        accessibilityButton.click()
    ]);
    // Verify opening edit setting page
    const currentUrl = await E2ETestHelper.getCurrentUrl(page);
    await expect(currentUrl).toContain('/edit');
    console.log('✅ Setting page to edit value of Dukungan Disabilitas');

    const selectElement = page.locator('#form-setting-aplikasi').getByRole('combobox');
    await selectElement.selectOption({label: 'Aktif'});
    await expect(selectElement).toHaveValue('1');
    console.log('✅ Change value to Aktif');

    const submitButton = page.getByRole('button', {name: 'Simpan'});
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        submitButton.click()
    ]);
    // Verify opening edit setting page
    const afterSaveUrl = await E2ETestHelper.getCurrentUrl(page);
    await expect(afterSaveUrl).toContain('/setting');
    console.log('✅ Back to setting page after edit value of Dukungan Disabilitas');

    const settingStatusNew = accessibilitySetting.getByRole('cell').nth(1);
    await expect(settingStatusNew).toHaveText('Aktif');
    console.log('✅ Setting is enabled: ', await settingStatusNew.textContent());
  });

  test('Check widget appears on homepage', async ({ page }) => {
    await page.goto('/');

    // Click accessibility button.
    const accsWidgetButton = page.getByRole('button', {name: 'Open Accessibility Menu'});
    await expect(accsWidgetButton).toBeVisible();
    console.log('✅ Accessibility widget has been loaded');

    await accsWidgetButton.click();
    await expect(page.locator('.asw-menu')).toBeVisible();
    console.log('✅ Accessibility widget menu appears after clicking the button');
  });

  test('Set accessibility widget setting disabled', async ({ page }) => {
    await page.goto('/setting/aplikasi');

    // Check Dukungan Disabilitas exists
    const settingsTable = page.locator('table#user-table');
    const settingsEntries = settingsTable.getByRole('row');
    const accessibilitySetting = settingsEntries.filter( {hasText: /dukungan disabilitas/i} )
    const settingStatus = accessibilitySetting.getByRole('cell').nth(1);
    await expect(settingStatus).toHaveText(/^\W+Aktif/);
    console.log('✅ Setting is enabled: ', await settingStatus.textContent());
    const accessibilityButton = accessibilitySetting.getByRole('button');
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        // E2ETestHelper.clickElement(page, accessibilityButton)
        accessibilityButton.click()
    ]);
    // Verify opening edit setting page
    const currentUrl = await E2ETestHelper.getCurrentUrl(page);
    await expect(currentUrl).toContain('/edit');
    console.log('✅ Setting page to edit value of Dukungan Disabilitas');

    const selectElement = page.locator('#form-setting-aplikasi').getByRole('combobox');
    await selectElement.selectOption({label: 'Tidak Aktif'});
    await expect(selectElement).toHaveValue('0');
    console.log('✅ Change value to Tidak Aktif');

    const submitButton = page.getByRole('button', {name: 'Simpan'});
    await Promise.all([
        page.waitForNavigation({ waitUntil: 'networkidle' }),
        submitButton.click()
    ]);
    // Disable accessibility setting
    const settingStatusDisabled = accessibilitySetting.getByRole('cell').nth(1);
    await expect(settingStatusDisabled).toHaveText('Tidak Aktif');
    console.log('✅ Setting is disabled: ', await settingStatusDisabled.textContent());
  });

  test("Check widget doesn't exist on homepage", async ({ page }) => {
    await page.goto('/');

    // Click accessibility button.
    const accsWidgetButton = page.getByRole('button', {name: 'Open Accessibility Menu'});
    await expect(accsWidgetButton).not.toBeAttached();
    console.log('✅ Accessibility widget has not loaded');

    await expect(page.locator('.asw-widget')).not.toBeAttached();
    console.log('✅ Make sure accessibility widget element is not present');
  });
});
