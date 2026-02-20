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

use App\Http\Middleware\Authenticate;
use App\Http\Middleware\CompleteProfile;
use App\Http\Middleware\GlobalShareMiddleware;
use App\Models\SettingAplikasi;
use Spatie\Permission\Middleware\PermissionMiddleware;
use Spatie\Permission\Middleware\RoleMiddleware;

class CrudTestCase extends TestCase
{
    use CreatesApplication;

    /**
     * Set up the test environment.
     */
    protected function setUp(): void
    {
        parent::setUp();

        $this->withViewErrors([]);
        $this->withoutMiddleware([Authenticate::class, RoleMiddleware::class, PermissionMiddleware::class, CompleteProfile::class, GlobalShareMiddleware::class]); // Disable middleware for this test
        
        // Disable database gabungan for testing - ensure setting is set to 0
        $exists = \Illuminate\Support\Facades\DB::table('das_setting')
            ->where('key', 'sinkronisasi_database_gabungan')
            ->exists();
        
        if ($exists) {
            \Illuminate\Support\Facades\DB::table('das_setting')
                ->where('key', 'sinkronisasi_database_gabungan')
                ->update(['value' => '0']);
        } else {
            \Illuminate\Support\Facades\DB::table('das_setting')->insert([
                'key' => 'sinkronisasi_database_gabungan',
                'value' => '0',
                'type' => 'boolean',
                'kategori' => 'sinkronisasi',
            ]);
        }
        
        // Clear any cached settings to force reload on next request
        \Illuminate\Support\Facades\Cache::forget('settings');
        \Illuminate\Support\Facades\Cache::forget('setting');
    }

    // Additional methods for CRUD tests can be added here
}
