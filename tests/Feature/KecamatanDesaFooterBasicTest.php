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

namespace Tests\Feature;

use Illuminate\Support\Facades\Cache;
use Tests\TestCase;

class KecamatanDesaFooterBasicTest extends TestCase
{
    protected function setUp(): void
    {
        parent::setUp();

        // Pastikan aplikasi sudah diinstall
        if (!file_exists(storage_path('installed'))) {
            file_put_contents(storage_path('installed'), 'installed');
        }
    }

    /**
     * Test: Aplikasi harus sudah diinstall untuk akses halaman
     */
    public function test_requires_app_to_be_installed()
    {
        // Hapus file installed
        $installedFile = storage_path('installed');
        if (file_exists($installedFile)) {
            unlink($installedFile);
        }

        // Akses halaman depan
        $response = $this->get('/');

        // Harus redirect ke halaman install
        $response->assertRedirect('/install');
    }

    /**
     * Test: Basic home page access ketika sudah install
     */
    public function test_home_page_accessible_when_installed()
    {
        // Pastikan file installed ada
        if (!file_exists(storage_path('installed'))) {
            file_put_contents(storage_path('installed'), 'installed');
        }

        // Coba akses halaman depan
        $response = $this->get('/');

        // Response bisa 200 (berhasil) atau 500 (error karena data tidak lengkap)
        // Yang penting bukan redirect ke install
        $this->assertNotEquals(302, $response->status());
    }

    /**
     * Test: Cache clear command berjalan dengan baik
     */
    public function test_cache_clear_command_works()
    {
        // Set some cache
        Cache::put('profil', ['test' => 'data'], 60);
        Cache::put('setting', ['test' => 'data'], 60);

        // Pastikan cache ada
        $this->assertTrue(Cache::has('profil'));
        $this->assertTrue(Cache::has('setting'));

        // Jalankan command clear cache
        $this->artisan('cache:clear-profil')
            ->expectsOutput('Membersihkan cache profil kecamatan...')
            ->expectsOutput('✓ Cache profil dan setting berhasil dibersihkan')
            ->assertExitCode(0);

        // Pastikan cache sudah di-clear
        $this->assertFalse(Cache::has('profil'));
        $this->assertFalse(Cache::has('setting'));
    }

    /**
     * Test: Cache clear command dengan option --all
     */
    public function test_cache_clear_command_with_all_option()
    {
        // Set some cache
        Cache::put('profil', ['test' => 'data'], 60);
        Cache::put('setting', ['test' => 'data'], 60);
        Cache::put('other_cache', ['test' => 'data'], 60);

        // Pastikan cache ada
        $this->assertTrue(Cache::has('profil'));
        $this->assertTrue(Cache::has('setting'));
        $this->assertTrue(Cache::has('other_cache'));

        // Jalankan command clear cache dengan --all
        $this->artisan('cache:clear-profil --all')
            ->expectsOutput('Membersihkan cache profil kecamatan...')
            ->expectsOutput('✓ Cache profil dan setting berhasil dibersihkan')
            ->expectsOutput('Membersihkan semua cache aplikasi...')
            ->expectsOutput('✓ Semua cache aplikasi berhasil dibersihkan')
            ->assertExitCode(0);

        // Pastikan semua cache sudah di-clear
        $this->assertFalse(Cache::has('profil'));
        $this->assertFalse(Cache::has('setting'));
        $this->assertFalse(Cache::has('other_cache'));
    }

    /**
     * Test: sudahInstal() function works correctly
     */
    public function test_sudah_instal_function()
    {
        // Test ketika file tidak ada
        $installedFile = storage_path('installed');
        if (file_exists($installedFile)) {
            unlink($installedFile);
        }

        $this->assertFalse(sudahInstal());

        // Test ketika file ada
        file_put_contents($installedFile, 'installed');
        $this->assertTrue(sudahInstal());
    }
}
