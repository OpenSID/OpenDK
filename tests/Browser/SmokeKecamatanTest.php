<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    // Truncate tables to avoid orphaned data on parallel tests
    \App\Models\DataDesa::query()->delete();
    \App\Models\DataSarana::query()->delete();

    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: KECAMATAN -> PROFIL
// =============================================================================
it('smoke test menu Kecamatan - Profil', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/profil');
    $this->page->assertPathIs('/data/profil');

    // Halaman Terbuka
    $this->page->assertSee('Profil');

    // Field-field tampil
    $this->page->assertPresent('[name="provinsi_id"]');
    $this->page->assertPresent('[name="kabupaten_id"]');
    $this->page->assertPresent('[name="kecamatan_id"]');
    $this->page->assertVisible('input[name="tahun_pembentukan"]');
    $this->page->assertVisible('input[name="dasar_pembentukan"]');
    $this->page->assertVisible('textarea[name="alamat"]');
    $this->page->assertVisible('input[name="kode_pos"]');
    $this->page->assertVisible('input[name="telepon"]');
    $this->page->assertVisible('input[name="email"]');

    // Tombol Pilih file dan Preview
    $this->page->assertVisible('input[name="file_struktur_organisasi"]');
    $this->page->assertVisible('input[name="file_logo"]');
    
    // Preview gambar diskip
    $this->page->assertPresent('textarea[name="sambutan"]');
    $this->page->assertPresent('textarea[name="visi"]');
    $this->page->assertPresent('textarea[name="misi"]');

})->group('smoke', 'smoke-kecamatan', 'browser');


// =============================================================================
// MENU: KECAMATAN -> DATA UMUM
// =============================================================================
it('smoke test menu Kecamatan - Data Umum', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/data-umum');
    $this->page->assertPathIs('/data/data-umum');

    // Tab Info wilayah, peta wilayah, lokasi kantor
    $this->page->assertSee('Info Wilayah');
    $this->page->assertSee('Peta Wilayah');
    $this->page->assertSee('Lokasi Kantor');

    $this->page->assertVisible('input[name="luas_wilayah"]');

    // Rich editor
    $this->page->assertPresent('textarea[name="sejarah"]');
    $this->page->assertPresent('textarea[name="tipologi"]');

    // Tombol batal dan simpan
    $this->page->assertSee('Simpan');
    $this->page->assertSee('Batal');

    // Tab Peta Wilayah
    // Tab map dan lokasi diskip

    // Tab Lokasi Kantor

})->group('smoke', 'smoke-kecamatan', 'browser');


// =============================================================================
// MENU: KECAMATAN -> DATA DESA
// =============================================================================
it('smoke test menu Kecamatan - Data Desa', function () {
    if (\App\Models\DataDesa::count() === 0) {
        \App\Models\DataDesa::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/data-desa');
    $this->page->assertPathIs('/data/data-desa');

    $this->page->assertVisible('#datadesa-table');

    // Tunggu DataTable selesai render (AJAX)
    sleep(2);

    // Minimal 1 data tampil

    // Tombol aksi peta tampil

})->group('smoke', 'smoke-kecamatan', 'browser');


// =============================================================================
// MENU: KECAMATAN -> DATA SARANA
// =============================================================================
it('smoke test menu Kecamatan - Data Sarana', function () {
    if (\App\Models\DataSarana::count() === 0) {
        // Karena Factory mungkin tidak ada atau belum pasti, gunakan manual array
        $desa = \App\Models\DataDesa::firstOrCreate(['nama' => 'Desa Dummy'], ['desa_id' => '1234567890']);
        \App\Models\DataSarana::create([
            'desa_id' => $desa->desa_id,
            'kategori' => 'puskesmas',
            'fasilitas' => 'Puskesmas',
            'jumlah' => 1
        ]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/data-sarana');
    $this->page->assertPathIs('/data/data-sarana');

    $this->page->assertPresent('select#list_desa');
    $this->page->assertPresent('select#kategori');



    $this->page->assertVisible('#datasarana-table');

    // Tunggu DataTable selesai render
    sleep(2);


})->group('smoke', 'smoke-kecamatan', 'browser');


// =============================================================================
// MENU: KECAMATAN -> PENGURUS
// =============================================================================
it('smoke test menu Kecamatan - Pengurus', function () {
    if (\App\Models\Pengurus::count() === 0) {
        \App\Models\Pengurus::factory()->create();
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/pengurus');
    $this->page->assertPathIs('/data/pengurus');

    $this->page->assertSee('Tambah');
    $this->page->assertSee('Bagan');
    $this->page->assertPresent('select#status');

    $this->page->assertVisible('#pengurus-table');

    sleep(2);

    // Aksi: rincian (arsip), edit, hapus, aktifasi

})->group('smoke', 'smoke-kecamatan', 'browser');


// =============================================================================
// MENU: KECAMATAN -> JABATAN
// =============================================================================
it('smoke test menu Kecamatan - Jabatan', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/data/jabatan');
    $this->page->assertPathIs('/data/jabatan');

    $this->page->assertVisible('#jabatan-table');

    sleep(2);


})->group('smoke', 'smoke-kecamatan', 'browser');

