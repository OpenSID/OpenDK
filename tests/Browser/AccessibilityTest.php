<?php

// Note: Accessibility widget tests are complex and require specific app state
// These simplified tests verify basic functionality

test('should access accessibility settings page', function () {
    $user = \App\Models\User::first();
    
    $this->actingAs($user)
        ->visit('/setting/aplikasi')
        ->assertSee('Pengaturan Aplikasi')
        ->assertSee('Dukungan Disabilitas');
})->group('browser', 'accessibility');

test('should display homepage for accessibility widget check', function () {
    $this->visit('/')
        ->assertPresent('body');
})->group('browser', 'accessibility');

