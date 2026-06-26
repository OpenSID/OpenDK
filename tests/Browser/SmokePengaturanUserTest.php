<?php

use Tests\BrowserTestCase;
use Laravel\Dusk\Browser;

uses(BrowserTestCase::class);

beforeEach(function () {
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PENGATURAN -> GRUP PENGGUNA
// =============================================================================
it('smoke test menu Pengaturan User - Grup Pengguna', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/role');
    $this->page->assertPathIs('/setting/role');

    $this->page->assertSee('Grup Pengguna');
    $this->page->assertPresent('.box-body table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> PENGGUNA
// =============================================================================
it('smoke test menu Pengaturan User - Pengguna', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/user');
    $this->page->assertPathIs('/setting/user');

    $this->page->assertSee('Pengguna');
    $this->page->assertPresent('#user-table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> GANTI PASSWORD
// =============================================================================
it('smoke test menu Pengaturan User - Ganti Password', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/profile/password');
    $this->page->assertPathIs('/profile/password');

    $this->page->assertSee('Ganti Password');
    $this->page->assertPresent('form[action*="password"]'); // form untuk update password
    $this->page->assertPresent('input[name="current_password"]');
    $this->page->assertPresent('input[name="password"]');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> OTP & 2FA
// =============================================================================
it('smoke test menu Pengaturan User - OTP & 2FA', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/otp-2fa');
    
    // We only assert see text because redirection might occur depending on settings
    $this->page->assertSee('OTP');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');
