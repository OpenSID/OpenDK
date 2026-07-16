<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    \App\Models\Pesan::query()->delete();
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PESAN -> PESAN MASUK
// =============================================================================

it('smoke test menu Pesan - Pesan Masuk', function () {
    if (\App\Models\Pesan::count() === 0) {
        \App\Models\Pesan::factory()->create(['jenis' => \App\Models\Pesan::PESAN_MASUK]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan');
    $this->page->assertPathIs('/pesan');

    // Memastikan tombol buat pesan tampil
    $this->page->assertSee('Buat Pesan');

    // Panel kategori tampil
    $this->page->assertSee('Kategori');

    // List kategori tampil
    $this->page->assertSee('Pesan Masuk');
    $this->page->assertSee('Pesan Keluar');
    $this->page->assertSee('Arsip');

    // Field pilih desa dan cari pesan
    $this->page->assertPresent('select[name="das_data_desa_id"]');
    $this->page->assertVisible('input#cari-pesan');

    // Tabel pesan tampil
    $this->page->assertVisible('.table-hover');

    // Minimal 1 data pesan tampil
    $this->page->assertScript(
        "document.querySelector('.table-hover tbody tr') !== null"
    );

})->group('smoke', 'smoke-pesan', 'browser');


// =============================================================================
// MENU: PESAN -> PESAN KELUAR
// =============================================================================

it('smoke test menu Pesan - Pesan Keluar', function () {
    if (\App\Models\Pesan::where('jenis', \App\Models\Pesan::PESAN_KELUAR)->count() === 0) {
        \App\Models\Pesan::factory()->create(['jenis' => \App\Models\Pesan::PESAN_KELUAR]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan/keluar');
    $this->page->assertPathIs('/pesan/keluar');

    // Memastikan tombol buat pesan tampil
    $this->page->assertSee('Buat Pesan');

    // Panel kategori tampil
    $this->page->assertSee('Kategori');

    // List kategori tampil
    $this->page->assertSee('Pesan Masuk');
    $this->page->assertSee('Pesan Keluar');
    $this->page->assertSee('Arsip');

    // Field pilih desa dan cari pesan
    $this->page->assertPresent('select[name="das_data_desa_id"]');
    $this->page->assertVisible('input#cari-pesan');

    // Tabel pesan tampil
    $this->page->assertVisible('.table-hover');

    // Minimal 1 data pesan tampil
    $this->page->assertScript(
        "document.querySelector('.table-hover tbody tr') !== null"
    );

})->group('smoke', 'smoke-pesan', 'browser');


// =============================================================================
// MENU: PESAN -> ARSIP
// =============================================================================

it('smoke test menu Pesan - Arsip', function () {
    if (\App\Models\Pesan::where('diarsipkan', 1)->count() === 0) {
        \App\Models\Pesan::factory()->create(['diarsipkan' => 1]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/pesan/arsip');
    $this->page->assertPathIs('/pesan/arsip');

    // Memastikan tombol buat pesan tampil
    $this->page->assertSee('Buat Pesan');

    // Panel kategori tampil
    $this->page->assertSee('Kategori');

    // List kategori tampil
    $this->page->assertSee('Pesan Masuk');
    $this->page->assertSee('Pesan Keluar');
    $this->page->assertSee('Arsip');

    // Field pilih desa dan cari pesan
    $this->page->assertPresent('select[name="das_data_desa_id"]');
    $this->page->assertVisible('input#cari-pesan');

    // Tabel pesan tampil
    $this->page->assertVisible('.table-hover');

    // Minimal 1 data pesan tampil
    $this->page->assertScript(
        "document.querySelector('.table-hover tbody tr') !== null"
    );

})->group('smoke', 'smoke-pesan', 'browser');
