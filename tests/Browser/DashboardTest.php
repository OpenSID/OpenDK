<?php

use Tests\BrowserAuthenticatedTestCase;

uses(BrowserAuthenticatedTestCase::class);

test('should display dashboard page correctly when authenticated', function () {
    // Visit dashboard page - user is already authenticated via BrowserAuthenticatedTestCase
    visit('/dashboard')
        ->wait(3) // Wait for page to load
        ->assertPathIs('/dashboard') // Verify we're redirected to dashboard
        ->assertSee('Dashboard');
})->group('browser', 'dashboard');

test('should display all required cards when authenticated', function () {
    // Visit dashboard page - user is already authenticated via BrowserAuthenticatedTestCase
    visit('/dashboard')
        ->wait(3) // Wait for page to load
        ->assertSee('Desa')
        ->assertSee('Penduduk')
        ->assertSee('Keluarga')
        ->assertSee('Bantuan')
        ->assertSee('Selengkapnya');
})->group('browser', 'dashboard');


test('should check dashboard loads successfully when authenticated', function () {
    // Visit dashboard page - user is already authenticated via BrowserAuthenticatedTestCase
    visit('/dashboard')
        ->wait(3) // Wait for page to load
        ->assertPresent('.content, .container, main');
})->group('browser', 'dashboard');
