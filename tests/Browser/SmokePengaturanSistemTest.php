<?php

use Tests\BrowserTestCase;
use Laravel\Dusk\Browser;

uses(BrowserTestCase::class);

beforeEach(function () {
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PENGATURAN -> MENU NAVIGASI
// =============================================================================
it('smoke test menu Pengaturan Sistem - Menu', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/navigation');
    $this->page->assertPathIs('/setting/navigation');

    $this->page->assertSee('Menu');
    $this->page->assertPresent('.box-body table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> WIDGET
// =============================================================================
it('smoke test menu Pengaturan Sistem - Widget', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/widget');
    $this->page->assertPathIs('/setting/widget');

    $this->page->assertSee('Widget');
    // Livewire tables use different structures, let's assert generic text presence
    $this->page->assertSee('Jenis Widget');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> SLIDE
// =============================================================================
it('smoke test menu Pengaturan Sistem - Slide', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/slide');
    $this->page->assertPathIs('/setting/slide');

    $this->page->assertSee('Slide');
    $this->page->assertVisible('#data-slide');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> THEMES
// =============================================================================
it('smoke test menu Pengaturan Sistem - Themes', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/themes');
    $this->page->assertPathIs('/setting/themes');

    $this->page->assertSee('Themes');
    $this->page->assertSee('Aktif'); 
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> APLIKASI
// =============================================================================
it('smoke test menu Pengaturan Sistem - Aplikasi', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/aplikasi');
    $this->page->assertPathIs('/setting/aplikasi');

    $this->page->assertSee('Aplikasi');
    $this->page->assertPresent('.box-body table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> INFO SISTEM
// =============================================================================
it('smoke test menu Pengaturan Sistem - Info Sistem', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/info-sistem');
    $this->page->assertPathIs('/setting/info-sistem');

    $this->page->assertSee('Info Sistem');
    $this->page->assertPresent('.nav-tabs'); // Ensure tabs exist
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> PENGATURAN DATABASE
// =============================================================================
it('smoke test menu Pengaturan Sistem - Pengaturan Database', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/backup-database');
    $this->page->assertPathIs('/setting/backup-database');

    $this->page->assertSee('Database');
    $this->page->assertPresent('.nav-tabs'); // Backup and Restore tabs
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> TOTAL PENGUNJUNG
// =============================================================================
it('smoke test menu Pengaturan Sistem - Total Pengunjung', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/counter');
    $this->page->assertPathIs('/counter');

    $this->page->assertSee('Total Pengunjung');
    $this->page->assertPresent('#container'); // Highcharts container
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');
