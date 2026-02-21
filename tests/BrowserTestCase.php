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

namespace Tests;

use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\SettingAplikasi;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\TestCase as BaseTestCase;

abstract class BrowserTestCase extends BaseTestCase
{
    use CreatesApplication;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Match APP_URL to the virtual host for correct redirection logic
        config(['app.url' => 'http://opendk.test/']);

        // Clear cache to ensure settings are fresh
        \Illuminate\Support\Facades\Cache::forget('setting');
        \Illuminate\Support\Facades\Cache::forget('profil');

        // Create necessary data for the homepage to load properly
        $this->createTestData();
        $this->withViewErrors([]);
        $this->withoutMiddleware(middleware: [CompleteProfile::class]); // Disable middleware for this test
        // disabled database gabungan for testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
        // enable accessibility support for testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'dukungan_disabilitas'],
            ['value' => '1']
        );
    }

    /**
     * Create test data needed for the homepage
     */
    protected function createTestData(): void
    {
        // Force deterministic profil data
        $profil = \App\Models\Profil::updateOrCreate(
            ['kecamatan_id' => '110101'],
            [
                'nama_kecamatan' => 'Test Kecamatan',
                'nama_kabupaten' => 'Test Kabupaten',
                'nama_provinsi' => 'Test Provinsi',
                'provinsi_id' => '11',
                'kabupaten_id' => '1101',
                'file_logo' => null,
                'sebutan_wilayah' => 'Kecamatan',
                'sebutan_kepala_wilayah' => 'Camat',
            ]
        );

        // Force deterministic DataUmum
        \App\Models\DataUmum::updateOrCreate(
            ['profil_id' => $profil->id],
            [
                'bts_wil_utara' => 'North',
                'bts_wil_timur' => 'East',
                'bts_wil_selatan' => 'South',
                'bts_wil_barat' => 'West',
                'lat' => '0',
                'lng' => '0',
                'path' => '[[[0,0]]]',
            ]
        );

        // Force deterministic Jabatans
        \App\Models\Jabatan::updateOrCreate(['jenis' => \App\Enums\JenisJabatan::Camat], ['nama' => 'Camat']);
        \App\Models\Jabatan::updateOrCreate(['jenis' => \App\Enums\JenisJabatan::Sekretaris], ['nama' => 'Sekretaris']);

        // Force deterministic setting aplikasi data
        $settings = [
            'sinkronisasi_database_gabungan' => '0',
            'dukungan_disabilitas' => '1',
            'judul_aplikasi' => 'Test OpenDK',
            'tte' => '0',
        ];

        foreach ($settings as $key => $value) {
            \App\Models\SettingAplikasi::updateOrCreate(
                ['key' => $key],
                [
                    'value' => $value,
                    'type' => $key === 'dukungan_disabilitas' ? 'boolean' : 'text',
                ]
            );
        }

        // Create theme data if it doesn't exist
        if (!\App\Models\Themes::count()) {
            \App\Models\Themes::create([
                'vendor' => 'opendk',
                'name' => 'default',
                'slug' => 'opendk/default',
                'active' => 1,
                'system' => 1,
            ]);
        }

        // Final cache clear to be absolutely sure
        \Illuminate\Support\Facades\Artisan::call('cache:clear');
    }
}
