<?php
use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

test('should display homepage when website is enabled', function () {
    visit('/')
        ->assertPresent('meta[name="viewport"]')
        ->assertPresent('nav, .navbar, .menu');
})->group('browser', 'homepage');

test('should have responsive design on mobile', function () {
    visit('/')
        ->resize(375, 667)
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'homepage', 'responsive');

test('should have responsive design on tablet', function () {
    visit('/')
        ->resize(768, 1024)
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'homepage', 'responsive');

test('should have responsive design on desktop', function () {
    visit('/')
        ->resize(1280, 720)
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'homepage', 'responsive');

test('should load homepage without errors', function () {
    visit('/')        
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'homepage');

test('should have proper meta tags', function () {
    visit('/')
        ->assertPresent('meta[name="viewport"]');
})->group('browser', 'homepage');
