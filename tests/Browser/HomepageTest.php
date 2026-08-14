<?php

use Tests\Browser\Pages\HomepagePage;
use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

test('should display homepage when website is enabled', function () {
    $page = new HomepagePage();
    visit($page->url())
        ->assertSee('Selamat Datang')
        ->assertPresent('.navbar-brand');
})->group('browser', 'homepage');

test('should have proper SEO and meta tags', function () {
    $page = new HomepagePage();
    visit($page->url())
        ->assertTitleContains('Beranda')
        ->assertTitleContains('Test OpenDK')
        ->assertPresent('meta[name="description"]')
        ->assertPresent('meta[property="og:title"]')
        ->assertPresent('link[rel="canonical"]');
})->group('browser', 'homepage', 'seo');

test('should have proper heading structure', function () {
    visit('/')
        ->assertPresent('.navbar-brand')
        ->assertSee('Berita Kecamatan');
})->group('browser', 'homepage', 'seo');

test('should have responsive design', function ($width, $height) {
    visit('/')
        ->resize($width, $height)
        ->assertPresent('meta[name="viewport"]');
})->with([
            'mobile' => [375, 667],
            'tablet' => [768, 1024],
            'desktop' => [1440, 900],
        ])->group('browser', 'homepage', 'responsive');
