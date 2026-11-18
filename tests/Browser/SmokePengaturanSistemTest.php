<?php

use Tests\BrowserTestCase;
use Laravel\Dusk\Browser;

uses(BrowserTestCase::class);

beforeEach(function () {
    $this->user = \Tests\Browser\SessionState::loginAdminUser();
});

// =============================================================================
// MENU: PENGATURAN -> MENU NAVIGASI
// =============================================================================
it('smoke test menu Pengaturan Sistem - Menu', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/navigation');
    $this->page->assertPathIs('/setting/navigation');

    $this->page->assertSee('Menu');
    $this->page->assertPresent('.box-body table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> WIDGET
// =============================================================================
it('smoke test menu Pengaturan Sistem - Widget', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/widget');
    $this->page->assertPathIs('/setting/widget');

    $this->page->assertSee('Widget');
    // Livewire tables use different structures, let's assert generic text presence
    $this->page->assertSee('Jenis Widget');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> SLIDE
// =============================================================================
it('smoke test menu Pengaturan Sistem - Slide', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/slide');
    $this->page->assertPathIs('/setting/slide');

    $this->page->assertSee('Slide');
    $this->page->assertVisible('#data-slide');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> THEMES
// =============================================================================
it('smoke test menu Pengaturan Sistem - Themes', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/themes');
    $this->page->assertPathIs('/setting/themes');

    $this->page->assertSee('Themes');
    $this->page->assertSee('Aktif'); 
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> APLIKASI
// =============================================================================
it('smoke test menu Pengaturan Sistem - Aplikasi', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/aplikasi');
    $this->page->assertPathIs('/setting/aplikasi');

    $this->page->assertSee('Aplikasi');
    $this->page->assertPresent('.box-body table');
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> INFO SISTEM
// =============================================================================
it('smoke test menu Pengaturan Sistem - Info Sistem', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/info-sistem');
    $this->page->assertPathIs('/setting/info-sistem');

    $this->page->assertSee('Info Sistem');
    $this->page->assertPresent('.nav-tabs'); // Ensure tabs exist
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> PENGATURAN DATABASE
// =============================================================================
it('smoke test menu Pengaturan Sistem - Pengaturan Database', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/backup-database');
    $this->page->assertPathIs('/setting/backup-database');

    $this->page->assertSee('Database');
    $this->page->assertPresent('.nav-tabs'); // Backup and Restore tabs
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// MENU: PENGATURAN -> TOTAL PENGUNJUNG
// =============================================================================
it('smoke test menu Pengaturan Sistem - Total Pengunjung', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/counter');
    $this->page->assertPathIs('/counter');

    $this->page->assertSee('Total Pengunjung');
    $this->page->assertPresent('#container'); // Highcharts container
    
    sleep(2);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// CHECKLIST MANUAL B: NAVIGASI - URUTAN NAIK / TURUN
// =============================================================================

/**
 * Siapkan satu induk dengan dua anak (Checklist Anak 1, Checklist Anak 2).
 */
function siapkanDataNavigasiAnak(): array
{
    \App\Models\Navigation::whereIn('name', ['Checklist Induk', 'Checklist Anak 1', 'Checklist Anak 2'])->delete();

    $induk = \App\Models\Navigation::create([
        'name' => 'Checklist Induk',
        'slug' => 'checklist-induk',
        'parent_id' => null,
        'type' => 2,
        'url' => 'https://example.com',
        'order' => 1,
        'status' => 1,
    ]);

    $anak1 = \App\Models\Navigation::create([
        'name' => 'Checklist Anak 1',
        'slug' => 'checklist-induk-checklist-anak-1',
        'parent_id' => $induk->id,
        'type' => 2,
        'url' => 'https://example.com/1',
        'order' => 1,
        'status' => 1,
    ]);

    $anak2 = \App\Models\Navigation::create([
        'name' => 'Checklist Anak 2',
        'slug' => 'checklist-induk-checklist-anak-2',
        'parent_id' => $induk->id,
        'type' => 2,
        'url' => 'https://example.com/2',
        'order' => 2,
        'status' => 1,
    ]);

    return [$induk, $anak1, $anak2];
}

// Selector toggle dropdown aksi pada baris ke-$baris tabel navigasi
function navigasiToggle(int $baris): string
{
    return "#data_navigation tbody tr:nth-child({$baris}) .dropdown-toggle";
}

// Selector tombol Naik/Turun di dropdown yang sedang terbuka (menu dipindah ke <body> saat dibuka)
function navigasiAksi(string $aksi): string
{
    // Dropdown Sinergi dipindah ke <body> saat dibuka, dropdown Navigasi tetap di dalam baris.
    return "body > ul.dropdown-menu[style*=\"display: block\"] [data-testid=\"{$aksi}\"] button, .btn-group.open [data-testid=\"{$aksi}\"] button";
}

// Selector sel nama pada baris ke-$baris tabel navigasi
function navigasiBaris(int $baris): string
{
    return "#data_navigation tbody tr:nth-child({$baris})";
}

it('smoke test Navigasi - Turun dan Naik mengubah urutan anak dan halaman tetap di induk yang sama', function () {
    [$induk] = siapkanDataNavigasiAnak();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, "/setting/navigation/{$induk->id}");
    $this->page->assertPathIs("/setting/navigation/{$induk->id}");
    $this->page->waitForText('Checklist Anak 2');

    // 1. Turun pada item pertama: urutan berubah, halaman tetap di induk yang sama
    $this->page->press(navigasiToggle(1))->press(navigasiAksi('btn-turun'));
    $this->page->assertSee('Navigasi berhasil diurutkan!');
    $this->page->assertPathIs("/setting/navigation/{$induk->id}");
    $this->page->assertSeeIn(navigasiBaris(1), 'Checklist Anak 2');
    $this->page->assertSeeIn(navigasiBaris(2), 'Checklist Anak 1');

    // 2. Naik pada item kedua: urutan kembali
    $this->page->press(navigasiToggle(2))->press(navigasiAksi('btn-naik'));
    $this->page->assertPathIs("/setting/navigation/{$induk->id}");
    $this->page->assertSeeIn(navigasiBaris(1), 'Checklist Anak 1');
    $this->page->assertSeeIn(navigasiBaris(2), 'Checklist Anak 2');

    // 3. Naik pada item paling atas: urutan tidak berubah dan tidak ada pesan error.
    //    Penanda di window dipakai untuk memastikan halaman sudah dimuat ulang setelah klik.
    $this->page->script("window.__sebelumNaik = true");
    $this->page->press(navigasiToggle(1))->press(navigasiAksi('btn-naik'));
    $this->page->assertScript("typeof window.__sebelumNaik === 'undefined'", true);
    $this->page->assertDontSee('Navigasi gagal diurutkan!');
    $this->page->assertSeeIn(navigasiBaris(1), 'Checklist Anak 1');
    $this->page->assertSeeIn(navigasiBaris(2), 'Checklist Anak 2');

    // 4. Setelah refresh, urutan tetap
    $this->page->refresh();
    $this->page->waitForText('Checklist Anak 2');
    $this->page->assertPathIs("/setting/navigation/{$induk->id}");
    $this->page->assertSeeIn(navigasiBaris(1), 'Checklist Anak 1');
    $this->page->assertSeeIn(navigasiBaris(2), 'Checklist Anak 2');
})->group('smoke', 'smoke-pengaturan', 'browser');

it('smoke test Navigasi - urutan anak di menu publik mengikuti urutan terbaru', function () {
    [$induk, $anak1, $anak2] = siapkanDataNavigasiAnak();

    // Kondisi setelah Turun pada Anak 1: Anak 2 kini di urutan pertama
    $anak1->update(['order' => 2]);
    $anak2->update(['order' => 1]);

    // Menu publik dibangun dari WebsiteApiRepository::getNavigations()
    $menu = app(\App\Repositories\WebsiteApiRepository::class)->getNavigations()->firstWhere('id', $induk->id);

    expect($menu)->not->toBeNull();
    // Urutan dicek apa adanya dari repository (tanpa diurutkan ulang di PHP),
    // karena menu publik menampilkan anak sesuai urutan yang dikembalikan repository.
    expect($menu->childrens->pluck('name')->values()->all())
        ->toBe(['Checklist Anak 2', 'Checklist Anak 1']);
})->group('smoke', 'smoke-pengaturan', 'browser');

// =============================================================================
// CHECKLIST MANUAL D: INFO SISTEM - DIALOG KONFIRMASI DAN AKSI KEBUTUHAN SISTEM
// =============================================================================

it('smoke test Info Sistem - storage:link menampilkan konfirmasi; Batal tidak menjalankan aksi, OK menjalankan', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/info-sistem');
    $this->page->assertPathIs('/setting/info-sistem');
    $this->page->press('a[href="#ekstensi"]');
    $this->page->assertVisible('#run-linkstorage');

    // Batal: dialog tertutup dan tidak ada hasil eksekusi
    $this->page->press('#run-linkstorage');
    $this->page->assertVisible('#modal-confirm');
    $this->page->assertSeeIn('#modal-confirm', 'Yakin Untuk Menjalankan php artisan storage:link?');
    $this->page->press('#modal-confirm .modal-footer button[data-dismiss="modal"]');
    $this->page->assertScript("!document.querySelector('#modal-confirm').classList.contains('in')", true);
    $this->page->assertDontSee('Berhasil menjalankan php artisan storage:link');

    // OK: perintah berjalan dan muncul notifikasi hasil
    $this->page->press('#run-linkstorage');
    $this->page->assertVisible('#modal-confirm');
    $this->page->press('#modal-confirm-yes');
    $this->page->assertSee('Berhasil menjalankan php artisan storage:link');
})->group('smoke', 'smoke-pengaturan', 'browser');

it('smoke test Info Sistem - migrasi langsung dijalankan tanpa dialog konfirmasi', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/info-sistem');
    $this->page->press('a[href="#ekstensi"]');
    $this->page->assertVisible('#run-migrasi');

    // Sesuai kode saat ini: form langsung ter-submit tanpa konfirmasi
    $this->page->press('#run-migrasi');
    $this->page->assertSee('Berhasil menjalankan migrasi');
})->group('smoke', 'smoke-pengaturan', 'browser');

it('smoke test Info Sistem - queue:listen berjalan via AJAX tanpa memuat ulang halaman', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/info-sistem');
    $this->page->press('a[href="#ekstensi"]');
    $this->page->assertVisible('#run-queue');

    // Penanda di window hilang jika halaman dimuat ulang
    $this->page->script("window.__tanpaReload = true");
    $this->page->press('#run-queue');
    $this->page->assertScript("window.__tanpaReload === true", true);
    $this->page->assertPathIs('/setting/info-sistem');
})->group('smoke', 'smoke-pengaturan', 'browser');

it('smoke test Pengaturan Database - dialog konfirmasi bersama tetap berfungsi', function () {
    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, '/setting/backup-database');
    $this->page->assertPathIs('/setting/backup-database');

    // Dialog konfirmasi dari modal-helper.js harus tampil dan bisa ditutup dengan Batal
    $this->page->script("openConfirm('Uji dialog konfirmasi', 'Konfirmasi', function () {})");
    $this->page->assertVisible('#modal-confirm');
    $this->page->press('#modal-confirm .modal-footer button[data-dismiss="modal"]');
    $this->page->assertScript("!document.querySelector('#modal-confirm').classList.contains('in')", true);
})->group('smoke', 'smoke-pengaturan', 'browser');


// Menu dropdown Navigasi yang terbuka (dipindah ke <body> saat dibuka, atau tetap di baris)
function navigasiMenu(string $testid): string
{
    return "body > ul.dropdown-menu[style*=\"display: block\"] [data-testid=\"{$testid}\"], .btn-group.open [data-testid=\"{$testid}\"]";
}

it('smoke test Navigasi - Ubah dan Hapus dari dropdown aksi berfungsi', function () {
    [$induk, $anak1] = siapkanDataNavigasiAnak();

    $this->page = \Tests\Browser\SessionState::loginAndNavigate($this->user, "/setting/navigation/{$induk->id}");
    $this->page->waitForText('Checklist Anak 2');

    // Ubah: membuka halaman ubah navigasi
    $this->page->press(navigasiToggle(1))->press(navigasiMenu('btn-edit'));
    $this->page->assertPathBeginsWith('/setting/navigation/edit/');

    // Hapus: dialog konfirmasi muncul, Batal tidak menghapus data
    $this->page->navigate("/setting/navigation/{$induk->id}");
    $this->page->waitForText('Checklist Anak 2');
    $this->page->press(navigasiToggle(1))->press(navigasiMenu('btn-hapus'));
    $this->page->assertVisible('#delete-modal');
    $this->page->press('#delete-modal-cancel');
    $this->page->assertScript("getComputedStyle(document.querySelector('#delete-modal')).display === 'none'", true);
    expect(\App\Models\Navigation::where('parent_id', $induk->id)->count())->toBe(2);
})->group('smoke', 'smoke-pengaturan', 'browser');
