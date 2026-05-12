<?php

use Tests\BrowserTestCase;
use Tests\Browser\Pages\LoginPage;
use Tests\Browser\Pages\DashboardPage;
use Tests\Browser\Pages\UserPage;

uses(BrowserTestCase::class);

test('admin can create a new user and assign roles', function () {
    // 0. Setup: Ensure Admin credentials and 2FA are set correctly
    $admin = \App\Models\User::where('email', 'admin@mail.com')->first();
    expect($admin)->not->toBeNull();
    $admin->update([
        'password' => 'Password123!',
        'two_fa_enabled' => 0
    ]);

    $dashboardPage = new DashboardPage();
    $userPage = new UserPage();

    $newUserData = [
        'name' => 'Test User Journey',
        'email' => 'test_journey_' . time() . '@example.com',
        'password' => 'Password123!',
        'address' => 'Jl. Testing No. 123',
        'role' => 'admin-desa',
    ];

    // 1. Initial State: Login as Admin
    $this->actingAs($admin);
    $browser = visit($dashboardPage->url());
    $browser->waitForText('Dashboard')
        ->assertSee('Dashboard');

    // 2. Journey: Create User
    $url = rtrim(config('app.url'), '/') . $userPage->url();
    $browser->page()->goto($url);
    $userPage->createUser($browser, $newUserData);

    // 3. Verification: Success Message and UI Status
    $browser->waitForText('berhasil')
        ->assertSee('User berhasil ditambahkan!')
        ->waitForText($newUserData['name'])
        ->assertSee($newUserData['name'])
        ->assertSee('Active');

    // 4. Verification: New User Session Switch
    $newUser = \App\Models\User::where('email', $newUserData['email'])->firstOrFail();
    expect($newUser->status)->toBe(1);

    // Authenticate as new user
    $this->actingAs($newUser);
    $browser->page()->goto(rtrim(config('app.url'), '/') . $dashboardPage->url());

    // 5. Final Verification: New User Dashboard Access
    $browser->waitForText('Dashboard')
        ->assertSee('Dashboard')
        ->assertSee($newUserData['name']);
})->group('browser', 'journey');
