<?php

use Tests\BrowserTestCase;
use App\Models\TingkatPendidikan;
use App\Models\PutusSekolah;
use App\Models\FasilitasPaud;

uses(BrowserTestCase::class);

beforeEach(function () {
    TingkatPendidikan::query()->delete();
    PutusSekolah::query()->delete();
    FasilitasPaud::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PENDIDIKAN -> TINGKAT PENDIDIKAN
// =============================================================================
it('smoke test menu Pendidikan - Tingkat Pendidikan', function () {
    if (TingkatPendidikan::count() === 0) {
        TingkatPendidikan::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/tingkat-pendidikan');
    $this->page->assertPathIs('/data/tingkat-pendidikan');

    $this->page->assertSee('Tingkat Pendidikan');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#tingkat-pendidikan');

    // Tunggu render
    sleep(2);

})->group('smoke', 'smoke-pendidikan', 'browser');


// =============================================================================
// MENU: PENDIDIKAN -> SISWA PUTUS SEKOLAH
// =============================================================================
it('smoke test menu Pendidikan - Siswa Putus Sekolah', function () {
    if (PutusSekolah::count() === 0) {
        PutusSekolah::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/putus-sekolah');
    $this->page->assertPathIs('/data/putus-sekolah');

    $this->page->assertSee('Siswa Putus Sekolah');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#imunisasi-table'); // ID table default dari view

    sleep(2);

})->group('smoke', 'smoke-pendidikan', 'browser');


// =============================================================================
// MENU: PENDIDIKAN -> FASILITAS PAUD
// =============================================================================
it('smoke test menu Pendidikan - Fasilitas PAUD', function () {
    if (FasilitasPaud::count() === 0) {
        FasilitasPaud::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/fasilitas-paud');
    $this->page->assertPathIs('/data/fasilitas-paud');

    $this->page->assertSee('Fasilitas PAUD');
    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#fasilitas-table');

    sleep(2);

})->group('smoke', 'smoke-pendidikan', 'browser');
