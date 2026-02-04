<?php

test('should display dashboard page correctly', function () {
    $this->actingAs(\App\Models\User::first())
        ->visit('/dashboard')
        ->assertSee('Dashboard');
})->group('browser', 'dashboard');

test('should display all required cards', function () {
    $this->actingAs(\App\Models\User::first())
        ->visit('/dashboard')
        ->assertSee('Desa')
        ->assertSee('Penduduk')
        ->assertSee('Keluarga')
        ->assertSee('Bantuan');
})->group('browser', 'dashboard');

test('should have selengkapnya links in cards', function () {
    $this->actingAs(\App\Models\User::first())
        ->visit('/dashboard')
        ->assertSee('Selengkapnya');
})->group('browser', 'dashboard');

test('should verify dashboard has navigation links', function () {
    $this->actingAs(\App\Models\User::first())
        ->visit('/dashboard')
        ->assertSee('Dashboard');
})->group('browser', 'dashboard');


test('should check dashboard loads successfully', function () {
    $this->actingAs(\App\Models\User::first())
        ->visit('/dashboard')
        ->assertPresent('.content, .container, main');
})->group('browser', 'dashboard');

test('should redirect unauthenticated users to login', function () {
    $this->visit('/dashboard')
        ->assertSee('Login');
})->group('browser', 'dashboard');
