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

namespace Tests\Feature\Permission;

use App\Models\User;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

describe('Route Permission Middleware', function () {
    beforeEach(function () {
        $this->seed(\Database\Seeders\RoleSpatieSeeder::class);
    });

    test('dashboard route is accessible when user has access.dashboard permission', function () {
        // Ensure permission exists
        $permission = Permission::firstOrCreate(['name' => 'access.dashboard', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        
        // Use givePermissionTo instead of syncPermissions
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
    });

    test('counter route is accessible when user has access.counter permission', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.counter', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/counter');

        $response->assertStatus(200);
    });

    test('informasi prosedur route requires permission', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.informasi.prosedur', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/informasi/prosedur');

        $response->assertStatus(200);
    });

    test('data penduduk route requires permission', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.data.penduduk', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/data/penduduk');

        $response->assertStatus(200);
    });
});