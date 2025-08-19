class WebSettingsHelper {
  constructor(page, authStateManager) {
    this.page = page;
    this.authStateManager = authStateManager;
  }

  // Navigate to admin settings page
  async navigateToSettings() {
    // Ensure we're authenticated
    const cookies = await this.authStateManager.getStoredCookies();
    if (cookies.length > 0) {
      await this.page.context().addCookies(cookies);
    }

    // Navigate to settings page
    const route = '/pengaturan/settings';

    try {
      await this.page.goto(route);
      await this.page.waitForLoadState('networkidle', { timeout: 10000 });

      // Check if we're on a settings page (look for table with settings)
      const hasSettingsTable = await this.page.locator('table, .table, .settings-table').count() > 0;
      if (hasSettingsTable) {
        console.log(`Found settings page at: ${route}`);
        return true;
      }
    } catch (error) {
      console.warn(`Could not access ${route}:`, error.message);
    }

    throw new Error('Could not find settings page');
  }

  // Find setting row by key/code and return the row element
  async findSettingRow(settingKey) {
    // Look for table row that contains the setting key
    const rowSelectors = [
      `tr:has(td:text("${settingKey}"))`,
    ];

    for (const selector of rowSelectors) {
      try {
        const row = this.page.locator(selector).first();
        if (await row.count() > 0) {
          console.log(`Found setting row for "${settingKey}" with selector: ${selector}`);
          return row;
        }
      } catch (error) {
        // Continue trying other selectors
      }
    }

    return null;
  }

  // Find and click edit button for a specific setting
  async clickEditButtonForSetting(settingKey) {
    const settingRow = await this.findSettingRow(settingKey);

    if (!settingRow) {
      throw new Error(`Setting row for "${settingKey}" not found`);
    }

    // Look for edit button in the row
    const editButtonSelectors = [
      'a[href*="edit"]' // Common selectors for edit buttons
    ];

    for (const selector of editButtonSelectors) {
      const editButton = settingRow.locator(selector).first();
    // get the closest element to the edit button

      if (await editButton.count() > 0) {
        console.log(`Found edit button for "${settingKey}" with selector: ${selector}`);
        // Get the href attribute text
        const href = await editButton.getAttribute('href');
        return href;
      }
    }

    throw new Error(`Edit button for setting "${settingKey}" not found`);
  }

  // Update website enabled setting via web interface
  async setUpdateSettingViaWeb(settingKey, value = 1) {
    await this.navigateToSettings();

    // Find and click edit button for website_enabled setting
    const link = await this.clickEditButtonForSetting(settingKey);
    await this.page.goto(link);

    // Now we should be on the edit form page
    await this.page.waitForLoadState('networkidle');

    // Find the value input field in the edit form
    const valueFieldSelectors = [
      'input[name="value"]',
      'select[name="value"]',
    ];

    let fieldFound = false;
    for (const selector of valueFieldSelectors) {
      const valueField = this.page.locator(selector);
      if (await valueField.count() > 0) {
        console.log(`Found value field with selector: ${selector}`);

        const tagName = await valueField.evaluate(el => el.tagName.toLowerCase());
        const newValue = value ? '1' : '0';

        if (tagName === 'select') {
          await valueField.selectOption(newValue);
        } else {
          await valueField.fill(newValue);
        }

        fieldFound = true;
        break;
      }
    }

    if (fieldFound) {
      await this.submitForm();
    } else {
      console.warn('Value field not found in edit form');
    }
  }

  // Submit form after editing
  async submitForm() {
    const submitSelectors = [
      'button[type="submit"]',
      'input[type="submit"]',
    ];

    for (const selector of submitSelectors) {
      const submitBtn = this.page.locator(selector);
      if (await submitBtn.count() > 0) {
        await submitBtn.click();
        await this.page.waitForLoadState('networkidle');
        console.log(`Submitted form using: ${selector}`);

        // Wait for success message or redirect back to settings list
        await this.page.waitForTimeout(2000);
        return true;
      }
    }

    console.warn('Submit button not found');
    return false;
  }

  // Check if settings page is accessible
  async isSettingsAccessible() {
    try {
      await this.navigateToSettings();
      return true;
    } catch (error) {
      return false;
    }
  }
}

module.exports = WebSettingsHelper;
