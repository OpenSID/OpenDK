<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    // Truncate tables to avoid orphaned data on parallel tests
    \App\Models\DataDesa::query()->delete();
    \App\Models\AkiAkb::query()->delete();
    \App\Models\Imunisasi::query()->delete();
    \App\Models\EpidemiPenyakit::query()->delete();
    \App\Models\ToiletSanitasi::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: KESEHATAN -> AKI & AKB
// =============================================================================
it('smoke test menu Kesehatan - AKI AKB', function () {
    if (\App\Models\AkiAkb::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\AkiAkb::create([
            'desa_id' => $desa->desa_id,
            'bulan' => 1,
            'tahun' => 2026,
            'aki' => 1,
            'akb' => 1
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/aki-akb');
    $this->page->assertPathIs('/data/aki-akb');

    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#aki-table');

    sleep(2);


})->group('smoke', 'smoke-kesehatan', 'browser');


// =============================================================================
// MENU: KESEHATAN -> IMUNISASI
// =============================================================================
it('smoke test menu Kesehatan - Imunisasi', function () {
    if (\App\Models\Imunisasi::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\Imunisasi::create([
            'desa_id' => $desa->desa_id,
            'bulan' => 1,
            'tahun' => 2026,
            'cakupan_imunisasi' => 10
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/imunisasi');
    $this->page->assertPathIs('/data/imunisasi');

    $this->page->assertSee('Impor');
    $this->page->assertSee('Ekspor');
    $this->page->assertPresent('select#list_desa');
    
    $this->page->assertVisible('#imunisasi-table');

    sleep(2);


})->group('smoke', 'smoke-kesehatan', 'browser');


// =============================================================================
// MENU: KESEHATAN -> EPIDEMI PENYAKIT
// =============================================================================
it('smoke test menu Kesehatan - Epidemi Penyakit', function () {
    if (\App\Models\EpidemiPenyakit::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\EpidemiPenyakit::create([
            'desa_id' => $desa->desa_id,
            'penyakit_id' => 1,
            'jumlah_penderita' => 1,
            'bulan' => 1,
            'tahun' => 2026
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/epidemi-penyakit');
    $this->page->assertPathIs('/data/epidemi-penyakit');

    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#aki-table');

    sleep(2);


})->group('smoke', 'smoke-kesehatan', 'browser');


// =============================================================================
// MENU: KESEHATAN -> TOILET DAN SANITASI
// =============================================================================
it('smoke test menu Kesehatan - Toilet dan Sanitasi', function () {
    if (\App\Models\ToiletSanitasi::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\ToiletSanitasi::create([
            'desa_id' => $desa->desa_id,
            'toilet' => 1,
            'sanitasi' => 1,
            'bulan' => 1,
            'tahun' => 2026
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/toilet-sanitasi');
    $this->page->assertPathIs('/data/toilet-sanitasi');

    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('#toilet-table');

    sleep(2);


})->group('smoke', 'smoke-kesehatan', 'browser');
