<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    // Truncate tables to avoid orphaned data on parallel tests
    \App\Models\DataDesa::query()->delete();
    \App\Models\Penduduk::query()->delete();
    \App\Models\Keluarga::query()->delete();
    \App\Models\Suplemen::query()->delete();
    \App\Models\LaporanPenduduk::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: KEPENDUDUKAN -> PENDUDUK
// =============================================================================
it('smoke test menu Kependudukan - Penduduk', function () {
    if (\App\Models\Penduduk::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\Penduduk::factory()->create(['desa_id' => $desa->desa_id, 'status_dasar' => 1, 'kk_level' => 1, 'no_kk' => '1234567890123456']);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/penduduk');
    $this->page->assertPathIs('/data/penduduk');

    $this->page->assertSee('Ekspor');
    $this->page->assertPresent('select#list_desa');

    $this->page->assertVisible('#penduduk-table');

    sleep(2);


})->group('smoke', 'smoke-kependudukan', 'browser');


// =============================================================================
// MENU: KEPENDUDUKAN -> KELUARGA
// =============================================================================
it('smoke test menu Kependudukan - Keluarga', function () {
    if (\App\Models\Keluarga::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\Keluarga::factory()->create(['desa_id' => $desa->desa_id, 'no_kk' => '1234567890123456']);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/keluarga');
    $this->page->assertPathIs('/data/keluarga');

    $this->page->assertSee('Export');
    $this->page->assertPresent('select#list_desa');

    $this->page->assertVisible('#keluarga-table');

    sleep(2);


})->group('smoke', 'smoke-kependudukan', 'browser');


// =============================================================================
// MENU: KEPENDUDUKAN -> DATA SUPLEMEN
// =============================================================================
it('smoke test menu Kependudukan - Data Suplemen', function () {
    if (\App\Models\Suplemen::count() === 0) {
        \App\Models\Suplemen::create([
            'nama' => 'Suplemen Dummy',
            'sasaran' => 1,
            'keterangan' => 'Keterangan',
            'slug' => 'suplemen-dummy'
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/data-suplemen');
    $this->page->assertPathIs('/data/data-suplemen');

    $this->page->assertVisible('#suplemen-table');

    sleep(2);


})->group('smoke', 'smoke-kependudukan', 'browser');


// =============================================================================
// MENU: KEPENDUDUKAN -> LAPORAN PENDUDUK
// =============================================================================
it('smoke test menu Kependudukan - Laporan Penduduk', function () {
    if (\App\Models\LaporanPenduduk::count() === 0) {
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\LaporanPenduduk::create([
            'judul' => 'Laporan Penduduk',
            'bulan' => 1,
            'tahun' => 2026,
            'keterangan' => 'Ket',
            'desa_id' => $desa->desa_id,
            'id_laporan_penduduk' => 1,
            'nama_file' => 'dummy.pdf'
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/laporan-penduduk');
    $this->page->assertPathIs('/data/laporan-penduduk');

    $this->page->assertPresent('select#list_desa');
    $this->page->assertVisible('table.table');

    sleep(2);


})->group('smoke', 'smoke-kependudukan', 'browser');
