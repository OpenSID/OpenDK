<?php

use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

beforeEach(function () {
    \App\Models\Surat::query()->delete();
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: LAYANAN SURAT -> PERMOHONAN
// =============================================================================

it('smoke test menu Layanan Surat - Permohonan', function () {
    if (\App\Models\Surat::where('status', \App\Enums\StatusSurat::Permohonan)->count() === 0) {
        \App\Models\Surat::factory()->create(['status' => \App\Enums\StatusSurat::Permohonan]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/surat/permohonan');
    $this->page->assertPathIs('/surat/permohonan');

    // Card permohonan tampil
    $this->page->assertSee('Permohonan');

    // Card ditolak tampil
    $this->page->assertSee('Ditolak');

    // Filter desa tampil
    $this->page->assertPresent('select#list_desa');

    // Datatable tampil
    $this->page->assertVisible('#pengurus-table');

    // Tunggu DataTable selesai render (AJAX)
    $this->page->assertScript(
        "new Promise((resolve) => {
            const check = () => {
                const table = document.querySelector('#pengurus-table');
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

    // Minimal 1 data tampil
    $this->page->assertMissing('.dataTables_empty');

})->group('smoke', 'smoke-surat', 'browser');


// =============================================================================
// MENU: LAYANAN SURAT -> ARSIP
// =============================================================================

it('smoke test menu Layanan Surat - Arsip', function () {
    if (\App\Models\Surat::where('status', \App\Enums\StatusSurat::Arsip)->count() === 0) { // status selesai/arsip
        \App\Models\Surat::factory()->create(['status' => \App\Enums\StatusSurat::Arsip]);
    }

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/surat/arsip');
    $this->page->assertPathIs('/surat/arsip');

    // Filter desa tampil
    $this->page->assertPresent('select#list_desa');

    // Datatable tampil
    $this->page->assertVisible('#pengurus-table');

    // Tunggu DataTable selesai render (AJAX)
    $this->page->assertScript(
        "new Promise((resolve) => {
            const check = () => {
                const table = document.querySelector('#pengurus-table');
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

    // Minimal 1 data tampil
    $this->page->assertMissing('.dataTables_empty');

})->group('smoke', 'smoke-surat', 'browser');


// =============================================================================
// MENU: LAYANAN SURAT -> PENGATURAN
// =============================================================================

it('smoke test menu Layanan Surat - Pengaturan', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/surat/pengaturan');
    $this->page->assertPathIs('/surat/pengaturan');

    // Field aktifasi modul TTE tampil
    $this->page->assertSee('Aktifkan Modul TTE');
    
    // Field url, username, password tampil
    $this->page->assertVisible('input[name="tte_api"]');
    $this->page->assertVisible('input[name="tte_username"]');
    $this->page->assertVisible('input[name="tte_password"]');
    
    // Field aktifasi pemeriksaan camat dan sekretaris
    $this->page->assertSee('Pemeriksaan Camat');
    $this->page->assertSee('Pemeriksaan Sekretaris');

    // Tombol batal dan simpan tampil
    $this->page->assertSee('Batal');
    $this->page->assertSee('Simpan');

})->group('smoke', 'smoke-surat', 'browser');
