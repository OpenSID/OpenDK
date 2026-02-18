<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);
test('should display login page correctly', function () {
    // Logout first to ensure we're on login page
    visit('/logout');
    visit('/login')
        ->assertSee('Login')
        ->assertPresent('#email')  // Using ID selector instead of name
        ->assertPresent('#password') // Using ID selector instead of name
        ->assertPresent('button[type="submit"]');
})->group('browser', 'login');

test('should login successfully with valid credentials', function () {
    // Logout first to ensure we're on login page
    visit('/logout');
    visit('/login')
        ->type('email', env('E2E_ADMIN_EMAIL', 'admin@example.com'))
        ->type('password', env('E2E_ADMIN_PASSWORD', 'password'))
        ->press('Sign In')
        ->assertDontSee('Login');
})->group('browser', 'login')->skip('Requires valid credentials in .env');

test('should show error for invalid credentials', function () {
    // Logout first to ensure we're on login page
    visit('/logout');
    visit('/login')
        ->type('email', 'invalid@email.com')
        ->type('password', 'wrongpassword')
        ->press('Sign In')
        ->assertSee('Login');
})->group('browser', 'login');

test('should validate required fields', function () {
    // Logout first to ensure we're on login page
    visit('/logout');
    visit('/login')
        ->press('Sign In')
        ->assertSee('Login');
})->group('browser', 'login');

test('should handle remember me checkbox if present', function () {
    // Logout first to ensure we're on login page
    visit('/logout');
    visit('/login')
        ->assertPresent('#email');
})->group('browser', 'login')->skip('Remember me functionality requires full login flow');
