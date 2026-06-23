<?php

use Tests\BrowserTestCase;
use App\Models\Lembaga;
use App\Models\KategoriLembaga;

uses(BrowserTestCase::class);

beforeEach(function () {
    Lembaga::query()->delete();
    KategoriLembaga::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: LEMBAGA -> LEMBAGA
// =============================================================================
it('smoke test menu Lembaga - Lembaga', function () {
    if (Lembaga::count() === 0) {
        Lembaga::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/lembaga');
    $this->page->assertPathIs('/data/lembaga');

    $this->page->assertSee('Lembaga');
    $this->page->assertSee('Tambah');
    // Filter desa (#list_desa) tidak ada di view lembaga/index.blade.php
    $this->page->assertVisible('#lembaga-table');

    // Tunggu render
    sleep(2);

})->group('smoke', 'smoke-lembaga', 'browser');


// =============================================================================
// MENU: LEMBAGA -> KATEGORI LEMBAGA
// =============================================================================
it('smoke test menu Lembaga - Kategori Lembaga', function () {
    if (KategoriLembaga::count() === 0) {
        KategoriLembaga::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/kategori-lembaga');
    $this->page->assertPathIs('/data/kategori-lembaga');

    $this->page->assertSee('Kategori Lembaga');
    $this->page->assertSee('Tambah');
    // Filter desa (#list_desa) tidak ada di view lembaga_kategori/index.blade.php
    $this->page->assertVisible('#kategori-lembaga-table');

    sleep(2);

})->group('smoke', 'smoke-lembaga', 'browser');
