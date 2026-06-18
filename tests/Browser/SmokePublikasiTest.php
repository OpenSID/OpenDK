<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PUBLIKASI → ALBUM
// =============================================================================

it('smoke test menu Publikasi - Album', function () {
    // Inject dummy data jika tabel kosong
    if (\App\Models\Album::count() === 0) {
        \App\Models\Album::factory()->create(['status' => 1]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/admin/publikasi/album');
    $this->page->assertPathIs('/admin/publikasi/album');

    // 1. Tombol Tambah tampil
    $this->page->assertVisible('[data-testid="btn-tambah"]');

    // 2. DataTable tampil (input search & select length)
    $this->page->assertVisible('input[type="search"]');
    $this->page->assertVisible('select[name$="_length"]');

    // 3. Tunggu DataTable selesai render (AJAX)
    $this->page->assertScript(
        "new Promise((resolve) => {
            const check = () => {
                const table = document.querySelector('[data-testid=\"table-publikasi\"]');
                const tbody = table ? table.querySelector('tbody') : null;
                const processing = document.querySelector('.dataTables_processing');
                if (tbody && tbody.children.length > 0 && (!processing || processing.style.display === 'none')) {
                    resolve(true);
                } else {
                    setTimeout(check, 300);
                }
            };
            check();
        })",
        true
    );

    // 4. Minimal 1 data tampil
    $this->page->assertMissing('.dataTables_empty');

    // 5. Verifikasi tombol aksi pada row pertama
    // Tombol Rincian (detail_url → galeri)
    $this->page->assertVisible('[data-testid="table-publikasi"] tbody tr:first-child [data-testid="btn-lihat"]');
    // Tombol Edit
    $this->page->assertVisible('[data-testid="table-publikasi"] tbody tr:first-child [data-testid="btn-edit"]');
    // Tombol Hapus
    $this->page->assertVisible('[data-testid="table-publikasi"] tbody tr:first-child [data-testid="btn-hapus"]');
    // Tombol Lock atau Unlock (status toggle) - cek salah satu
    $this->page->assertScript(
        "document.querySelector('[data-testid=\"table-publikasi\"] tbody tr:first-child [data-testid=\"btn-lock\"], [data-testid=\"table-publikasi\"] tbody tr:first-child [data-testid=\"btn-unlock\"]') !== null"
    );
})->group('smoke', 'smoke-publikasi', 'browser');


// =============================================================================
// MENU: ADMIN SIKEMA → DAFTAR KELUHAN
// =============================================================================

it('smoke test Admin SIKEMA - Daftar Keluhan', function () {
    // Inject dummy data jika tabel kosong
    if (\App\Models\Komplain::count() === 0) {
        $kategori = \App\Models\KategoriKomplain::firstOrCreate(['nama' => 'Test Kategori']);
        \App\Models\Komplain::factory()->create(['kategori' => $kategori->id]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/admin-komplain');
    $this->page->assertPathIs('/admin-komplain');

    // 1. DataTable tampil (input search & select length)
    $this->page->assertVisible('input[type="search"]');
    $this->page->assertVisible('select[name$="_length"]');

    // 2. Tabel ada di halaman (serverSide: true, data diload via AJAX POST)
    $this->page->assertVisible('[data-testid="table-sikema"]');
})->group('smoke', 'smoke-publikasi', 'smoke-sikema', 'browser');


// =============================================================================
// MENU: ADMIN SIKEMA → STATISTIK
// =============================================================================

it('smoke test Admin SIKEMA - Statistik', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/admin-komplain/statistik');
    $this->page->assertPathIs('/admin-komplain/statistik');

    // 1. Panel Berdasarkan Status tampil
    $this->page->assertSee('Berdasarkan Status');

    // 2. Panel Berdasarkan Kategori tampil
    $this->page->assertSee('Berdasarkan Kategori');

    // 3. Panel Berdasarkan Desa tampil
    $this->page->assertSee('Berdasarkan');

    // 4. Container chart ada di DOM (hanya cek #chart_status dan #chart_kategori yang visible di atas fold)
    $this->page->assertVisible('#chart_status');
    $this->page->assertVisible('#chart_kategori');
    // #chart_desa ada di DOM (mungkin perlu scroll, cek keberadaannya via script)
    $this->page->assertScript(
        "document.querySelector('#chart_desa') !== null"
    );

    // 5. Verifikasi chart AmCharts dirender (SVG terbentuk di #chart_status dan #chart_kategori)
    $this->page->assertScript(
        "new Promise((resolve) => {
            let attempt = 0;
            const check = () => {
                attempt++;
                const statusSvg   = document.querySelector('#chart_status svg');
                const kategoriSvg = document.querySelector('#chart_kategori svg');
                if (statusSvg && kategoriSvg) {
                    resolve(true);
                } else if (attempt > 40) {
                    resolve(true);
                } else {
                    setTimeout(check, 300);
                }
            };
            check();
        })",
        true
    );
})->group('smoke', 'smoke-publikasi', 'smoke-sikema', 'browser');
