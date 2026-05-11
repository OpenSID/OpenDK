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

describe('Sidebar Menu Visibility', function () {
    beforeEach(function () {
        $this->seed(\Database\Seeders\RoleSpatieSeeder::class);
    });

    test('user sees dashboard menu when has access.dashboard permission', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.dashboard', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
    });

    test('user sees informasi.prosedur menu when has permission', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.informasi.prosedur', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Prosedur');
    });

    test('user sees informasi.artikel menu when has permission', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.informasi.artikel', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Artikel');
    });

    test('sidebar renders without error for user with multiple permissions', function () {
        // Ensure all permissions exist
        $perms = [
            'access.dashboard',
            'access.informasi.prosedur',
            'access.data.penduduk',
            'access.data.lembaga',
        ];
        foreach ($perms as $perm) {
            Permission::firstOrCreate(['name' => $perm, 'guard_name' => 'web']);
        }
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        foreach ($perms as $perm) {
            $user->givePermissionTo($perm);
        }
        $this->actingAs($user);

        $response = $this->get('/dashboard');

        $response->assertStatus(200);
        $response->assertSee('Dashboard');
        $response->assertSee('Prosedur');
        $response->assertSee('Penduduk');
        $response->assertSee('Lembaga');
    });
});