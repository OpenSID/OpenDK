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

namespace Tests\Feature\Settings;

use App\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;
use Tests\CrudTestCase;

beforeEach(function () {
    // Clear existing roles and permissions for clean test
    Role::whereNotIn('name', ['super-admin'])->delete();
    Permission::whereNotIn('name', ['super-admin'])->delete();
});

describe('Role and Permission CRUD', function () {
    test('index displays role list view', function () {
        $response = $this->get(route('setting.role.index'));

        $response->assertStatus(200);
        $response->assertViewIs('role.index');
        $response->assertViewHas('page_title', 'Group Pengguna');
        $response->assertViewHas('page_description', 'Daftar Data');
    });    

    test('store creates new role successfully', function () {
        $validData = [
            'name' => 'test-role',
            'display_name' => 'Test Role',
            'description' => 'Test Role Description',
        ];

        $response = $this->post(route('setting.role.store'), $validData);

        $response->assertRedirect();

        $this->assertDatabaseHas('roles', [
            'name' => 'test-role',
        ]);
    });
    
    test('update updates role successfully', function () {
        $role = Role::create([
            'name' => 'test-role-update',
            'display_name' => 'Test Role Update',
            'description' => 'Test Description',
        ]);

        $updateData = [
            'name' => 'updated-role',
            'display_name' => 'Updated Role Display',
            'description' => 'Updated Description',
        ];

        $response = $this->put(route('setting.role.update', $role->id), $updateData);

        $response->assertRedirect();

        $this->assertDatabaseHas('roles', [
            'id' => $role->id,
            'name' => 'updated-role',
        ]);
    });

    test('destroy deletes role successfully', function () {
        $role = Role::create([
            'name' => 'test-role-delete',
            'display_name' => 'Test Role Delete',
            'description' => 'Test Description',
        ]);

        $response = $this->delete(route('setting.role.destroy', $role->id));

        $response->assertRedirect();

        $this->assertDatabaseMissing('roles', [
            'id' => $role->id,
        ]);
    });
   
    test('role can be assigned to user', function () {
        $role = Role::create([
            'name' => 'test-role-assign',
            'display_name' => 'Test Role Assign',
            'description' => 'Test Description',
        ]);

        $user = User::factory()->create();

        $user->assignRole($role);

        $this->assertDatabaseHas('model_has_roles', [
            'model_id' => $user->id,
            'role_id' => $role->id,
        ]);
    });
});
