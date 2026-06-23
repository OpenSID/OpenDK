<?php

use Tests\BrowserTestCase;
use App\Models\TipePotensi;
use App\Models\KategoriKomplain;
use App\Models\TipeRegulasi;
use App\Models\JenisPenyakit;
use App\Models\JenisDokumen;
use App\Models\CoaType;

uses(BrowserTestCase::class);

beforeEach(function () {
    TipePotensi::query()->delete();
    KategoriKomplain::query()->delete();
    TipeRegulasi::query()->delete();
    JenisPenyakit::query()->delete();
    JenisDokumen::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PENGATURAN -> KATEGORI POTENSI
// =============================================================================
it('smoke test menu Pengaturan Kategori - Tipe Potensi', function () {
    if (TipePotensi::count() === 0) {
        TipePotensi::create(['nama_kategori' => 'Potensi A', 'slug' => 'potensi-a']);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/tipe-potensi');
    $this->page->assertPathIs('/setting/tipe-potensi');

    $this->page->assertSee('Kategori Potensi'); 
    $this->page->assertVisible('#data_tipe_potensi');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> KATEGORI KOMPLAIN
// =============================================================================
it('smoke test menu Pengaturan Kategori - Kategori Komplain', function () {
    if (KategoriKomplain::count() === 0) {
        KategoriKomplain::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/komplain-kategori');
    $this->page->assertPathIs('/setting/komplain-kategori');

    $this->page->assertSee('Kategori Komplain'); 
    $this->page->assertVisible('#data-komplain-kategori');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> TIPE REGULASI
// =============================================================================
it('smoke test menu Pengaturan Kategori - Tipe Regulasi', function () {
    if (TipeRegulasi::count() === 0) {
        TipeRegulasi::create(['nama' => 'Regulasi A', 'slug' => 'regulasi-a']);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/tipe-regulasi');
    $this->page->assertPathIs('/setting/tipe-regulasi');

    $this->page->assertSee('Tipe Regulasi'); 
    $this->page->assertVisible('#data-tipe-regulasi');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> JENIS PENYAKIT
// =============================================================================
it('smoke test menu Pengaturan Kategori - Jenis Penyakit', function () {
    if (JenisPenyakit::count() === 0) {
        JenisPenyakit::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/jenis-penyakit');
    $this->page->assertPathIs('/setting/jenis-penyakit');

    $this->page->assertSee('Jenis Penyakit'); 
    $this->page->assertVisible('#data-penyakit');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> JENIS DOKUMEN
// =============================================================================
it('smoke test menu Pengaturan Kategori - Jenis Dokumen', function () {
    if (JenisDokumen::count() === 0) {
        JenisDokumen::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/jenis-dokumen');
    $this->page->assertPathIs('/setting/jenis-dokumen');

    $this->page->assertSee('Jenis Dokumen'); 
    $this->page->assertVisible('#data_jenis_dokumen');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> COA
// =============================================================================
it('smoke test menu Pengaturan Kategori - COA', function () {
    if (CoaType::count() === 0) {
        CoaType::create(['type_name' => 'Aset', 'type_number' => 1]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/coa');
    $this->page->assertPathIs('/setting/coa');

    $this->page->assertSee('Daftar COA'); 
    $this->page->assertVisible('#data-coa >> nth=0');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');
