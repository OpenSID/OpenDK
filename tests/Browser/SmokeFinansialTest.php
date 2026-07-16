<?php

use Tests\BrowserTestCase;
use App\Models\AnggaranRealisasi;
use App\Models\AnggaranDesa;
use App\Models\LaporanApbdes;

uses(BrowserTestCase::class);

beforeEach(function () {
    AnggaranRealisasi::query()->delete();
    AnggaranDesa::query()->delete();
    LaporanApbdes::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: FINANSIAL -> ANGGARAN DAN REALISASI
// =============================================================================
it('smoke test menu Finansial - Anggaran dan Realisasi', function () {
    if (AnggaranRealisasi::count() === 0) {
        AnggaranRealisasi::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/anggaran-realisasi');
    $this->page->assertPathIs('/data/anggaran-realisasi');

    $this->page->assertSee('Anggaran & Realisasi');
    $this->page->assertVisible('#anggaran-table');

    // Tunggu render
    sleep(2);

})->group('smoke', 'smoke-finansial', 'browser');


// =============================================================================
// MENU: FINANSIAL -> APBDes
// =============================================================================
it('smoke test menu Finansial - APBDes', function () {
    if (AnggaranDesa::count() === 0) {
        AnggaranDesa::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/anggaran-desa');
    $this->page->assertPathIs('/data/anggaran-desa');

    $this->page->assertSee('APBDes');
    $this->page->assertSee('Ekspor');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#anggaran-table');

    sleep(2);

})->group('smoke', 'smoke-finansial', 'browser');


// =============================================================================
// MENU: FINANSIAL -> LAPORAN APBDes
// =============================================================================
it('smoke test menu Finansial - Laporan APBDes', function () {
    if (LaporanApbdes::count() === 0) {
        LaporanApbdes::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/laporan-apbdes');
    $this->page->assertPathIs('/data/laporan-apbdes');

    $this->page->assertSee('Laporan APBDes');
    $this->page->assertSee('Ekspor');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#apbdes-table');

    sleep(2);

})->group('smoke', 'smoke-finansial', 'browser');
