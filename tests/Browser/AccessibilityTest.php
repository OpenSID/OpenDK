<?php

use Tests\BrowserAuthenticatedTestCase;

uses(Tests\BrowserAuthenticatedTestCase::class);

test('should display accessibility settings page', function () {
    // Accessibility settings list in a table
    visit('/setting/aplikasi')
        ->assertSee('Pengaturan Aplikasi')
        ->assertSee('Dukungan Disabilitas');
})->group('browser', 'accessibility', 'admin');

test('should have accessibility widgets on homepage when enabled', function () {
    // Check for sienna accessibility script on public homepage
    visit('/')
        ->assertPresent('script[src*="sienna.min.js"]');
})->group('browser', 'accessibility', 'public');
