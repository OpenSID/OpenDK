<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);
test('homepage can be accessed', function () {
    visit('/')
        ->assertSee('OpenDK');
})->group('browser');

test('login page has correct elements', function () {
    visit('/login')
        ->assertSee('Login')
        ->assertPresent('input[name="email"]')
        ->assertPresent('input[name="password"]');
})->group('browser');
