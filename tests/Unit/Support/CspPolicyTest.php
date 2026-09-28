<?php

use App\Support\CspPolicy;

/*
|--------------------------------------------------------------------------
| Content Security Policy
|--------------------------------------------------------------------------
|
| Galeri dapat menampilkan media dari luar (Google Drive, YouTube, Vimeo, dan
| situs desa), sehingga CSP harus mengizinkan sumber tersebut.
|
*/

it('allows youtube and vimeo frames for embedded videos', function () {
    $policy = (new CspPolicy())->toHeader();

    expect($policy)->toContain('*.youtube.com')
        ->and($policy)->toContain('*.vimeo.com')
        ->and($policy)->toContain('https://drive.google.com');
});

it('allows external media sources for gallery videos', function () {
    $directive = (new CspPolicy())->directive('media-src');

    expect($directive)->toContain("'self'")
        ->and($directive)->toContain('https:')
        ->and($directive)->toContain('blob:');
});

it('allows external images for gallery thumbnails', function () {
    $directive = (new CspPolicy())->directive('img-src');

    expect($directive)->toContain("'self'")
        ->and($directive)->toContain('data:')
        ->and($directive)->toContain('blob:');
});

it('includes the database gabungan api in connect-src', function () {
    config(['setting.api_server_database_gabungan' => 'https://api.gabungan.test']);

    expect((new CspPolicy())->toHeader())->toContain('connect-src')
        ->toContain('https://api.gabungan.test');
});

it('keeps a restrictive baseline for scripts and objects', function () {
    $policy = new CspPolicy();

    expect($policy->directive('default-src'))->toBe("'self'")
        ->and($policy->directive('object-src'))->toBe("'self' blob:")
        ->and($policy->directive('frame-ancestors'))->toBe("'self'")
        ->and($policy->directive('base-uri'))->toBe("'self'");
});
