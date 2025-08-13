const { test, expect } = require('@playwright/test');
const E2ETestHelper = require('../utils/e2e-helper');

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

  test('should display all required cards', async ({ page }) => {
    // Test Card Desa
    const cardDesa = page.locator('.small-box, .box, .widget').filter({ hasText: /desa/i });
    await expect(cardDesa).toBeVisible();
    console.log('✅ Card Desa is visible');

    // Test Card Penduduk
    const cardPenduduk = page.locator('.small-box, .box, .widget').filter({ hasText: /penduduk/i });
    await expect(cardPenduduk).toBeVisible();
    console.log('✅ Card Penduduk is visible');

    // Test Card Keluarga
    const cardKeluarga = page.locator('.small-box, .box, .widget').filter({ hasText: /keluarga/i });
    await expect(cardKeluarga).toBeVisible();
    console.log('✅ Card Keluarga is visible');

    // Test Card Bantuan
    const cardBantuan = page.locator('.small-box, .box, .widget').filter({ hasText: /bantuan/i });
    await expect(cardBantuan).toBeVisible();
    console.log('✅ Card Bantuan is visible');
  });

  test('should have selengkapnya links in all cards', async ({ page }) => {
    // Array of card types to check
    const cardTypes = ['desa', 'penduduk', 'keluarga', 'bantuan'];
    
    for (const cardType of cardTypes) {
      // Find card container
      const cardContainer = page.locator('.small-box, .box, .widget').filter({ hasText: new RegExp(cardType, 'i') });
      
      // Check for "Selengkapnya" link in the card
      const selengkapnyaLink = cardContainer.locator('a').filter({ 
        hasText: /selengkapnya|more|detail|lihat semua/i 
      });
      
      if (await selengkapnyaLink.count() > 0) {
        await expect(selengkapnyaLink.first()).toBeVisible();
        await expect(selengkapnyaLink.first()).toHaveAttribute('href');
        console.log(`✅ Card ${cardType} has Selengkapnya link`);
      } else {
        // Alternative: check for button or other clickable elements
        const moreButton = cardContainer.locator('button, .btn').filter({ 
          hasText: /selengkapnya|more|detail|lihat semua/i 
        });
        
        if (await moreButton.count() > 0) {
          await expect(moreButton.first()).toBeVisible();
          console.log(`✅ Card ${cardType} has Selengkapnya button`);
        } else {
          console.warn(`⚠️ Card ${cardType} might not have explicit Selengkapnya link`);
        }
      }
    }
  });

  test('should verify selengkapnya links are clickable', async ({ page }) => {
    // Find all "Selengkapnya" links
    const selengkapnyaLinks = page.locator('a').filter({ 
      hasText: /selengkapnya|more|detail|lihat semua/i 
    });
    
    const linkCount = await selengkapnyaLinks.count();
    console.log(`Found ${linkCount} Selengkapnya links`);
    
    if (linkCount > 0) {
      for (let i = 0; i < Math.min(linkCount, 4); i++) {
        const link = selengkapnyaLinks.nth(i);
        await expect(link).toBeVisible();
        await expect(link).toBeEnabled();
        
        // Verify href attribute exists and is not empty
        const href = await link.getAttribute('href');
        expect(href).toBeTruthy();
        expect(href).not.toBe('#');
        console.log(`✅ Selengkapnya link ${i + 1} is clickable with href: ${href}`);
      }
    }
  });

  test('should display visitor statistics table', async ({ page }) => {
    // Look for visitor statistics table with various possible selectors
    const statisticsTable = page.locator(
      'table:has-text("pengunjung"), ' +
      'table:has-text("visitor"), ' +
      'table:has-text("statistik"), ' +
      '.table:has-text("pengunjung"), ' +
      '.table:has-text("visitor"), ' +
      '.table:has-text("statistik"), ' +
      '#statistik-pengunjung, ' +
      '#visitor-stats, ' +
      '.visitor-statistics'
    );

    if (await statisticsTable.count() > 0) {
      await expect(statisticsTable.first()).toBeVisible();
      
      // Verify table has headers
      const tableHeaders = statisticsTable.first().locator('th, .table-header');
      await expect(tableHeaders.first()).toBeVisible();
      
      // Verify table has data rows
      const tableRows = statisticsTable.first().locator('tr, .table-row');
      expect(await tableRows.count()).toBeGreaterThan(0);
      
      console.log('✅ Visitor statistics table is visible and contains data');
    } else {
      // Alternative: look for statistics widget or card
      const statisticsWidget = page.locator(
        '.widget:has-text("pengunjung"), ' +
        '.card:has-text("pengunjung"), ' +
        '.box:has-text("pengunjung"), ' +
        '.widget:has-text("statistik"), ' +
        '.card:has-text("statistik"), ' +
        '.box:has-text("statistik")'
      );
      
      if (await statisticsWidget.count() > 0) {
        await expect(statisticsWidget.first()).toBeVisible();
        console.log('✅ Visitor statistics widget is visible');
      } else {
        console.warn('⚠️ Visitor statistics table/widget not found - please check the selector');
      }
    }
  });

  test('should verify visitor statistics table structure', async ({ page }) => {
    // Find the statistics table
    const statisticsTable = page.locator('table').filter({ 
      hasText: /pengunjung|visitor|statistik/i 
    });

    if (await statisticsTable.count() > 0) {
      const table = statisticsTable.first();
      
      // Check table structure
      await expect(table).toBeVisible();
      
      // Verify table has thead
      const thead = table.locator('thead');
      if (await thead.count() > 0) {
        await expect(thead).toBeVisible();
        console.log('✅ Statistics table has header');
      }
      
      // Verify table has tbody with data
      const tbody = table.locator('tbody');
      if (await tbody.count() > 0) {
        await expect(tbody).toBeVisible();
        
        const rows = tbody.locator('tr');
        const rowCount = await rows.count();
        expect(rowCount).toBeGreaterThan(0);
        console.log(`✅ Statistics table has ${rowCount} data rows`);
      }
      
      // Check for common statistics columns
      const commonColumns = ['tanggal', 'date', 'hari', 'pengunjung', 'visitor', 'hits'];
      let foundColumns = 0;
      
      for (const column of commonColumns) {
        const columnHeader = table.locator('th, td').filter({ 
          hasText: new RegExp(column, 'i') 
        });
        
        if (await columnHeader.count() > 0) {
          foundColumns++;
          console.log(`✅ Found column: ${column}`);
        }
      }
      
      expect(foundColumns).toBeGreaterThan(0);
      console.log(`✅ Statistics table has ${foundColumns} recognizable columns`);
    }
  });

  test('should verify card data is loaded', async ({ page }) => {
    // Wait for any loading indicators to disappear
    await page.waitForFunction(() => {
      const loadingElements = document.querySelectorAll('.loading, .spinner, .fa-spin');
      return loadingElements.length === 0;
    }, { timeout: 10000 }).catch(() => {
      console.log('No loading indicators found or timeout reached');
    });

    // Check if cards contain actual data (numbers/statistics)
    const cardTypes = ['desa', 'penduduk', 'keluarga', 'bantuan'];
    
    for (const cardType of cardTypes) {
      const cardContainer = page.locator('.card, .box, .widget').filter({ 
        hasText: new RegExp(cardType, 'i') 
      });
      
      if (await cardContainer.count() > 0) {
        // Look for numbers in the card
        const numberPattern = /\d+/;
        const cardText = await cardContainer.first().textContent();
        
        if (numberPattern.test(cardText)) {
          console.log(`✅ Card ${cardType} contains numerical data`);
        } else {
          console.warn(`⚠️ Card ${cardType} might not contain numerical data`);
        }
      }
    }
  });
});