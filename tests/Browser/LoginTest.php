<?php

use Tests\BrowserTestCase;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\DashboardPage;

uses(BrowserTestCase::class);

test('should display login page correctly', function () {
    $loginPage = new LoginPage();
    visit($loginPage->url())
        ->assertSee('Login')
        ->assertPresent('#email')
        ->assertPresent('#password');
})->group('browser', 'login');

test('should login successfully with valid credentials', function () {
    $user = \App\Models\User::factory()->create([
        'email' => 'login_test@example.com',
        'password' => bcrypt('password123'),
        'status' => 1,
    ]);
    $user->assignRole('super-admin');

    $loginPage = new LoginPage();
    $dashboardPage = new DashboardPage();

    $browser = visit($loginPage->url());
    $loginPage->login($browser, 'login_test@example.com', 'password123');

    // Debug: check if we see any error messages if login failed
    $browser->assertDontSee('Kredensial yang diberikan tidak cocok')
        ->assertDontSee('Captcha code diperlukan')
        ->assertSee('Dashboard');
})->group('browser', 'login');

test('should show error for invalid credentials', function () {
    $loginPage = new LoginPage();
    $browser = visit($loginPage->url());
    $loginPage->login($browser, 'invalid@email.com', 'wrongpassword');

    $browser->assertSee('Kredensial yang diberikan tidak cocok');
})->group('browser', 'login');

test('should validate required fields', function () {
    $loginPage = new LoginPage();
    visit($loginPage->url())
        ->press('Sign In')
        ->assertSee('Login');
})->group('browser', 'login');

test('should handle logout flow', function () {
    $user = \App\Models\User::factory()->create([
        'status' => 1,
    ]);

    $loginPage = new LoginPage();
    $dashboardPage = new DashboardPage();

    $this->actingAs($user);

    $browser = visit($dashboardPage->url());
    $browser->assertSee('Dashboard');

    $dashboardPage->logout($browser);

    $browser->assertSee('Login');
})->group('browser', 'login');
