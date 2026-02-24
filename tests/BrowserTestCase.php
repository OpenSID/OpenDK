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
    use CreatesApplication, DatabaseTransactions;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();
        // Create necessary data for the homepage to load properly
        $this->createTestData();
        $this->withViewErrors([]);
        $this->withoutMiddleware(middleware: [CompleteProfile::class]); // Disable middleware for this test
        // disabled database gabungan for testing
        SettingAplikasi::updateOrCreate(
            ['key' => 'sinkronisasi_database_gabungan'],
            ['value' => '0']
        );
    }

    /**
     * Create test data needed for the homepage
     */
    protected function createTestData(): void
    {
        // Create profil data if it doesn't exist
        if (!\App\Models\Profil::count()) {
            \App\Models\Profil::create([
                'nama_kecamatan' => 'Test Kecamatan',
                'nama_kabupaten' => 'Test Kabupaten',
                'nama_provinsi' => 'Test Provinsi',
                'file_logo' => null,
                'sebutan_wilayah' => 'Kecamatan',
                'sebutan_kepala_wilayah' => 'Camat',
            ]);
        }
        
        // Create setting aplikasi data if needed
        if (!\App\Models\SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->exists()) {
            \App\Models\SettingAplikasi::create([
                'key' => 'sinkronisasi_database_gabungan',
                'value' => '0',
            ]);
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
    }
}
