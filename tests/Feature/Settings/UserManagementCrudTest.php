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

use App\Models\User;
use App\Models\Pengurus;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Storage;
use Tests\CrudTestCase;

beforeEach(function () {
    Storage::fake('public');

    // Create test roles
    Role::firstOrCreate(['name' => 'super-admin']);
    Role::firstOrCreate(['name' => 'administrator-website']);
    Role::firstOrCreate(['name' => 'user']);

    // Create test users
    $superAdmin = User::factory()->create(['email' => 'superadmin@test.com']);
    $superAdmin->assignRole('super-admin');

    $admin = User::factory()->create(['email' => 'admin@test.com']);
    $admin->assignRole('administrator-website');
});

describe('User Management CRUD', function () {
    test('index displays user list view', function () {
        $response = $this->get(route('setting.user.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.index');
        $response->assertViewHas('page_title', 'Pengguna');
        $response->assertViewHas('page_description', 'Daftar Data');
    });

    test('create displays user creation form', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();

        $response = $this->actingAs($superAdmin)->get(route('setting.user.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.create');
        $response->assertViewHas('page_title', 'Pengguna');
        $response->assertViewHas('page_description', 'Tambah Data');
    });

    test('store creates new user successfully', function () {
        Storage::fake('public');

        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $pengurus = Pengurus::factory()->create();
        $role = Role::where('name', 'administrator-website')->first();

        $validData = [
            'name' => 'New User',
            'email' => 'newuser@test.com',
            'password' => 'password@123OK',
            'password_confirmation' => 'password@123OK',
            'phone' => '08123456789',
            'address' => 'Test Address',
            'pengurus_id' => $pengurus->id,
            'role' => [$role->name],
            'status' => 1,
        ];

        $response = $this->actingAs($superAdmin)->post(route('setting.user.store'), $validData);

        $response->assertRedirect(route('setting.user.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'email' => 'newuser@test.com',
            'name' => 'New User',
        ]);
    });

    test('store fails with invalid data', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();

        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'address' => '',
        ];

        $response = $this->actingAs($superAdmin)->post(route('setting.user.store'), $invalidData);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'address']);
    });

    test('edit displays edit form for super admin', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();

        $response = $this->actingAs($superAdmin)->get(route('setting.user.edit', $targetUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $targetUser);
    });

    test('update updates user successfully', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();
        $role = Role::where('name', 'administrator-website')->first();

        $updateData = [
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
            'phone' => '08987654321',
            'address' => 'Updated Address',
            'role' => [$role->name],
        ];

        $response = $this->actingAs($superAdmin)->put(route('setting.user.update', $targetUser->id), $updateData);

        $response->assertRedirect(route('setting.user.index'));
        $response->assertSessionHas('success');

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'name' => 'Updated Name',
            'email' => 'updated@test.com',
        ]);
    });

    test('destroy deactivates user successfully', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();
        $targetUser->update(['status' => 1]);

        $response = $this->actingAs($superAdmin)->post(route('setting.user.destroy', $targetUser->id));

        $response->assertRedirect(route('setting.user.index'));

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'status' => 0,
        ]);
    });

    test('active activates user successfully', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();
        $targetUser->update(['status' => 0]);

        $response = $this->actingAs($superAdmin)->post(route('setting.user.active', $targetUser->id));

        $response->assertRedirect(route('setting.user.index'));

        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'status' => 1,
        ]);
    });

    test('getDataUser returns JSON response for DataTables', function () {
        $user = User::first();

        $response = $this->actingAs($user)->get(route('setting.user.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'email',
                    'address',
                    'status',
                    'id',
                    'phone',
                    'created_at',
                ],
            ],
        ]);
    });

    test('validation requires unique email', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $existingUser = User::first();

        $duplicateData = [
            'name' => 'Another User',
            'email' => $existingUser->email,
            'password' => 'password@123OK',
            'password_confirmation' => 'password@123OK',
            'address' => 'Test Address',
        ];

        $response = $this->actingAs($superAdmin)->post(route('setting.user.store'), $duplicateData);

        $response->assertSessionHasErrors('email');
    });

    test('validation requires strong password', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();

        $weakPasswordData = [
            'name' => 'Test User',
            'email' => 'test@test.com',
            'password' => '123',
            'password_confirmation' => '123',
            'address' => 'Test Address',
        ];

        $response = $this->actingAs($superAdmin)->post(route('setting.user.store'), $weakPasswordData);

        $response->assertSessionHasErrors('password');
    });

    test('permanentDestroy soft deletes user successfully', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();

        $response = $this->actingAs($superAdmin)->delete(
            route('setting.user.permanent-destroy', $targetUser->id)
        );

        $response->assertRedirect(route('setting.user.index'));

        $this->assertSoftDeleted('users', [
            'id' => $targetUser->id,
        ]);
    });

    test('soft deleted user does not appear in user list', function () {
        $superAdmin = User::whereHas('roles', fn($q) => $q->where('name', 'super-admin'))->first();
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();

        $targetUser->delete();

        $response = $this->actingAs($superAdmin)->get(route('setting.user.getdata'));

        $response->assertStatus(200);
        $response->assertJsonMissing(['id' => $targetUser->id]);
    });
});
