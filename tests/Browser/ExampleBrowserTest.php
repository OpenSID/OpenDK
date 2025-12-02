<?php

test('homepage can be accessed', function () {
    $this->visit('/')
        ->assertSee('OpenDK');
})->group('browser');

test('login page has correct elements', function () {
    $this->visit('/login')
        ->assertSee('Login')
        ->assertPresent('input[name="email"]')
        ->assertPresent('input[name="password"]');
})->group('browser');
