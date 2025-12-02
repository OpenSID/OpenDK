<?php

test('should display homepage when website is enabled', function () {
    $this->visit('/')
        ->assertPresent('body')
        ->assertPresent('nav, .navbar, .menu');
})->group('browser', 'homepage');

test('should have responsive design on mobile', function () {
    $this->visit('/')
        ->resize(375, 667)
        ->assertPresent('body');
})->group('browser', 'homepage', 'responsive');

test('should have responsive design on tablet', function () {
    $this->visit('/')
        ->resize(768, 1024)
        ->assertPresent('body');
})->group('browser', 'homepage', 'responsive');

test('should have responsive design on desktop', function () {
    $this->visit('/')
        ->resize(1280, 720)
        ->assertPresent('body');
})->group('browser', 'homepage', 'responsive');

test('should load homepage without errors', function () {
    $this->visit('/')
        ->assertPresent('body');
})->group('browser', 'homepage');

test('should have proper meta tags', function () {
    $this->visit('/')
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'homepage');
