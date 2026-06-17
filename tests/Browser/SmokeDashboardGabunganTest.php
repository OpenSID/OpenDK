<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

/**
 * Smoke Test: Dashboard — Mode Database Gabungan
 *
 * Memastikan fungsionalitas inti halaman Dashboard berjalan dengan benar
 * saat mode database gabungan aktif (sinkronisasi_database_gabungan = '1').
 *
 * Data kartu (Desa, Penduduk, Keluarga, Program Bantuan) diambil dari API
 * eksternal yang di-mock menggunakan Http::fake() Laravel. Karena
 * pest-plugin-browser menjalankan Laravel HTTP server dalam proses PHP yang
 * sama dengan test runner, Http::fake() berlaku saat controller memanggil
 * service layer (DesaService, PendudukService, KeluargaService, BantuanService).
 *
 * @group smoke
 * @group smoke-gabungan
 * @group browser
 */

use Illuminate\Support\Facades\Http;
use Tests\BrowserTestCase;

uses(BrowserTestCase::class);

/**
 * Konstanta URL API mock untuk konsistensi antar test.
 */
const SMOKE_API_SERVER = 'https://api.example.com';

/**
 * Data mock yang dikembalikan oleh masing-masing endpoint API.
 * Nilai ini deterministik sehingga test bisa memverifikasi angka yang tampil.
 */
const SMOKE_MOCK_DESA      = 12;
const SMOKE_MOCK_PENDUDUK  = 15420;
const SMOKE_MOCK_KELUARGA  = 4200;
const SMOKE_MOCK_BANTUAN   = 8;

/**
 * Setup sebelum setiap test:
 * 1. Aktifkan mode database gabungan via SettingAplikasi + config
 * 2. Pasang Http::fake() untuk 4 endpoint API yang dipanggil DashboardController
 */
beforeEach(function () {
    // Aktifkan mode database gabungan
    $this->setModeGabungan(SMOKE_API_SERVER);

    // Mock semua API endpoint yang dipanggil DashboardController
    // saat mode database gabungan aktif.
    // Urutan penting: pattern lebih spesifik harus didefinisikan lebih awal.
    Http::fake([
        // DesaService::jumlahDesa() → GET /api/v1/desa?filter[kode_kecamatan]=...
        SMOKE_API_SERVER . '/api/v1/desa*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => SMOKE_MOCK_DESA]],
        ], 200),

        // PendudukService::jumlahPenduduk() → GET /api/v1/opendk/sync-penduduk-opendk?...
        SMOKE_API_SERVER . '/api/v1/opendk/sync-penduduk-opendk*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => SMOKE_MOCK_PENDUDUK]],
        ], 200),

        // KeluargaService::jumlahKeluarga() → GET /api/v1/keluarga?filter[kode_kecamatan]=...
        SMOKE_API_SERVER . '/api/v1/keluarga*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => SMOKE_MOCK_KELUARGA]],
        ], 200),

        // BantuanService::jumlahBantuan() → GET /api/v1/bantuan?filter[kode_kecamatan]=...
        SMOKE_API_SERVER . '/api/v1/bantuan*' => Http::response([
            'data' => [],
            'meta' => ['pagination' => ['total' => SMOKE_MOCK_BANTUAN]],
        ], 200),

        // Wildcard fallback — endpoint lain (mis. DesaService untuk navbar)
        // dikembalikan sebagai array kosong agar tidak error
        SMOKE_API_SERVER . '/*' => Http::response(['data' => []], 200),

        // Fallback global untuk request ke URL lain
        '*' => Http::response([], 200),
    ]);
});

// ─────────────────────────────────────────────────────────────────────────────
// Test 1: Halaman berhasil dibuka
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): halaman dashboard berhasil dibuka', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        ->assertSee('Dashboard');
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 2: Kartu Desa tampil
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): kartu desa tampil', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        ->assertVisible('@card-desa')
        ->assertSee(config('setting.sebutan_desa', 'Desa'));
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 3: Kartu Penduduk tampil
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): kartu penduduk tampil', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        ->assertVisible('@card-penduduk')
        ->assertSee('Penduduk');
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 4: Kartu Keluarga tampil
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): kartu keluarga tampil', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        ->assertVisible('@card-keluarga')
        ->assertSee('Keluarga');
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 5: Kartu Program Bantuan tampil
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): kartu program bantuan tampil', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        ->assertVisible('@card-program-bantuan')
        ->assertSee('Program Bantuan');
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 6: Tab "Top 10 Halaman Terpopuler" tampil dan data tabel ada
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): tab top 10 halaman terpopuler tampil', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        // Tab link tampil
        ->assertVisible('@tab-top-pages')
        ->assertSee('Top 10 Halaman Terpopuler')
        // Pane aktif tampil
        ->assertVisible('@pane-top-pages')
        // Header tabel tampil
        ->assertSee('URL')
        ->assertSee('Page Views');
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 7: Tab "User Agent" tampil
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): tab user agent tampil', function () {
    $user = \App\Models\User::first();

    visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard')
        ->assertVisible('@tab-user-agent')
        ->assertSee('User Agent');
})->group('smoke', 'smoke-gabungan', 'browser');

// ─────────────────────────────────────────────────────────────────────────────
// Test 8: Chart "User Agent" tampil dan berhasil dirender
//
// Highcharts merender chart sebagai SVG. Test mengklik tab User Agent
// untuk memicu render, lalu polling sampai elemen SVG muncul di dalam
// container chart (#browser-chart). Ini membuktikan chart berhasil dirender.
// ─────────────────────────────────────────────────────────────────────────────

it('smoke (gabungan): chart user agent berhasil dirender', function () {
    $user = \App\Models\User::first();

    $page = visit('/_pest/login/' . $user->id)
        ->navigate('/dashboard')
        ->assertPathIs('/dashboard');

    // Klik tab User Agent untuk memicu render chart Highcharts
    $page->click('@tab-user-agent');

    // Beri waktu sebentar untuk Bootstrap tab transition selesai
    // dan Highcharts mulai merender (data sudah inline dari PHP)
    $page->wait(1);

    // Polling sampai SVG Highcharts muncul di dalam container chart-browser.
    // Highcharts merender <svg> setelah data di-pass dari PHP (inline JSON).
    // Timeout implisit: assertScript menggunakan Promise dengan setTimeout polling.
    $page->assertScript(
        "new Promise((resolve, reject) => {
            let attempts = 0;
            const maxAttempts = 30;
            const check = () => {
                const svg = document.querySelector('[data-testid=\"chart-browser\"] svg');
                if (svg) {
                    resolve(true);
                } else if (++attempts >= maxAttempts) {
                    resolve(false);
                } else {
                    setTimeout(check, 300);
                }
            };
            check();
        })",
        true
    );

    // Konfirmasi bahwa setidaknya satu chart container memiliki SVG di dalamnya
    $page->assertPresent('[data-testid="chart-browser"] svg');
})->group('smoke', 'smoke-gabungan', 'browser');
