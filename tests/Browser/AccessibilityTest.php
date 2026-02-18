<?php

use Tests\BrowserAuthenticatedTestCase;

uses(BrowserAuthenticatedTestCase::class);

test('should access accessibility settings page', function () {
    visit('/setting/aplikasi')
        ->assertSee('Pengaturan Aplikasi')
        ->assertSee('Dukungan Disabilitas');
})->group('browser', 'accessibility');

test('should display homepage for accessibility widget check', function () {
    visit('/')
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'accessibility');
