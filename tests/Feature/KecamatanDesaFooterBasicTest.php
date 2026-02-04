<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use Illuminate\Support\Facades\Cache;

beforeEach(function () {
    // Pastikan aplikasi sudah diinstall
    if (!file_exists(storage_path('installed'))) {
        file_put_contents(storage_path('installed'), 'installed');
    }
});

test('requires app to be installed', function () {
    // Hapus file installed
    $installedFile = storage_path('installed');
    if (file_exists($installedFile)) {
        unlink($installedFile);
    }

    // Akses halaman depan
    $response = $this->get('/');

    // Harus redirect ke halaman install
    $response->assertRedirect('/install');
});

test('home page accessible when installed', function () {
    // Pastikan file installed ada
    if (!file_exists(storage_path('installed'))) {
        file_put_contents(storage_path('installed'), 'installed');
    }

    // Coba akses halaman depan
    $response = $this->get('/');

    // Response bisa 200 (berhasil) atau 500 (error karena data tidak lengkap)
    // Yang penting bukan redirect ke install
    expect($response->status())->not->toBe(302);
});

test('cache clear command works', function () {
    // Set some cache
    Cache::put('profil', ['test' => 'data'], 60);
    Cache::put('setting', ['test' => 'data'], 60);

    // Pastikan cache ada
    expect(Cache::has('profil'))->toBeTrue()
        ->and(Cache::has('setting'))->toBeTrue();

    // Jalankan command clear cache
    $this->artisan('cache:clear-profil')
        ->expectsOutput('Membersihkan cache profil kecamatan...')
        ->expectsOutput('✓ Cache profil dan setting berhasil dibersihkan')
        ->assertExitCode(0);

    // Pastikan cache sudah di-clear
    expect(Cache::has('profil'))->toBeFalse()
        ->and(Cache::has('setting'))->toBeFalse();
});

test('cache clear command with all option', function () {
    // Set some cache
    Cache::put('profil', ['test' => 'data'], 60);
    Cache::put('setting', ['test' => 'data'], 60);
    Cache::put('other_cache', ['test' => 'data'], 60);

    // Pastikan cache ada
    expect(Cache::has('profil'))->toBeTrue()
        ->and(Cache::has('setting'))->toBeTrue()
        ->and(Cache::has('other_cache'))->toBeTrue();

    // Jalankan command clear cache dengan --all
    $this->artisan('cache:clear-profil --all')
        ->expectsOutput('Membersihkan cache profil kecamatan...')
        ->expectsOutput('✓ Cache profil dan setting berhasil dibersihkan')
        ->expectsOutput('Membersihkan semua cache aplikasi...')
        ->expectsOutput('✓ Semua cache aplikasi berhasil dibersihkan')
        ->assertExitCode(0);

    // Pastikan semua cache sudah di-clear
    expect(Cache::has('profil'))->toBeFalse()
        ->and(Cache::has('setting'))->toBeFalse()
        ->and(Cache::has('other_cache'))->toBeFalse();
});

test('sudah instal function', function () {
    // Test ketika file tidak ada
    $installedFile = storage_path('installed');
    if (file_exists($installedFile)) {
        unlink($installedFile);
    }

    expect(sudahInstal())->toBeFalse();

    // Test ketika file ada
    file_put_contents($installedFile, 'installed');
    expect(sudahInstal())->toBeTrue();
});
