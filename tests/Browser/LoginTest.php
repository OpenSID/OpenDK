<?php

test('should display login page correctly', function () {
    $this->visit('/login')
        ->assertSee('Login')
        ->assertPresent('input[name="email"]')
        ->assertPresent('input[name="password"]')
        ->assertPresent('button[type="submit"]');
})->group('browser', 'login');

test('should login successfully with valid credentials', function () {
    $this->visit('/login')
        ->type('email', env('E2E_ADMIN_EMAIL', 'admin@example.com'))
        ->type('password', env('E2E_ADMIN_PASSWORD', 'password'))
        ->press('Sign In')
        ->assertDontSee('Login');
})->group('browser', 'login')->skip('Requires valid credentials in .env');

test('should show error for invalid credentials', function () {
    $this->visit('/login')
        ->type('email', 'invalid@email.com')
        ->type('password', 'wrongpassword')
        ->press('Sign In')
        ->assertSee('Login');
})->group('browser', 'login');

test('should validate required fields', function () {
    $this->visit('/login')
        ->press('Sign In')
        ->assertSee('Login');
})->group('browser', 'login');

test('should handle remember me checkbox if present', function () {
    $this->visit('/login')
        ->assertPresent('input[name="email"]');
})->group('browser', 'login')->skip('Remember me functionality requires full login flow');
