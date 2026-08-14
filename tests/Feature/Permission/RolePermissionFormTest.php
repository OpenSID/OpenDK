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

use App\Models\Role;
use App\Models\User;
use Spatie\Permission\Models\Permission;
use Tests\TestCase;

describe('Role Permission Form Display', function () {
    beforeEach(function () {
        $this->seed(\Database\Seeders\RoleSpatieSeeder::class);
    });

    test('role permission form displays permission list', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.setting.role', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $role = Role::create([
            'name' => 'test-role-form',
            'display_name' => 'Test Role Form',
            'description' => 'Test Description',
        ]);

        $response = $this->get(route('setting.role.edit', $role->id));

        $response->assertStatus(200);
        $response->assertSee('access.dashboard');
        $response->assertSee('access.informasi');
        $response->assertSee('access.data.penduduk');
    });

    test('permission name displays as friendly name on edit page', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.setting.role', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $role = Role::create([
            'name' => 'test-role-friendly',
            'display_name' => 'Test Role Friendly',
            'description' => 'Test Description',
        ]);

        $response = $this->get(route('setting.role.edit', $role->id));

        $response->assertStatus(200);
        // Check translated/friendly names are displayed
        $response->assertSee('Beranda');
        $response->assertSee('Informasi');
    });

    test('all permissions from seeder are displayed in permission list', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.setting.role', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $role = Role::create([
            'name' => 'test-role-all-perms',
            'display_name' => 'Test Role All Permissions',
            'description' => 'Test Description',
        ]);

        $response = $this->get(route('setting.role.edit', $role->id));

        $response->assertStatus(200);
        
        // Verify all parent permissions are present
        $response->assertSee('access.dashboard');
        $response->assertSee('access.informasi');
        $response->assertSee('access.data');
        $response->assertSee('access.publikasi');
        $response->assertSee('access.setting');
    });

    test('child permissions are displayed under parent', function () {
        $permission = Permission::firstOrCreate(['name' => 'access.setting.role', 'guard_name' => 'web']);
        Permission::firstOrCreate(['name' => 'access.informasi.prosedur', 'guard_name' => 'web']);
        
        $user = User::first();
        if (!$user) {
            $user = User::factory()->create();
        }
        $user->givePermissionTo($permission);
        $this->actingAs($user);

        $role = Role::create([
            'name' => 'test-role-child',
            'display_name' => 'Test Role Child',
            'description' => 'Test Description',
        ]);

        $response = $this->get(route('setting.role.edit', $role->id));

        $response->assertStatus(200);
        // Child permissions should also be displayed
        $response->assertSee('access.informasi.prosedur');
    });
});