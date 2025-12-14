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
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace Tests\Feature;

use App\Models\User;
use App\Models\Pengurus;
use Spatie\Permission\Models\Role;
use Illuminate\Foundation\Testing\DatabaseTransactions;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\Hash;
use Tests\CrudTestCase;

class UserControllerTest extends CrudTestCase
{
    use DatabaseTransactions, WithFaker;

    protected function setUp(): void
    {
        parent::setUp();
        
        // Create test roles
        $this->createTestRoles();
        
        // Create test users
        $this->createTestUsers();
    }

    /**
     * Test index method displays user list view.
     *
     * @return void
     */
    public function test_index_displays_user_list_view()
    {
        $user = User::first();
        
        $response = $this->actingAs($user)->get(route('setting.user.index'));

        $response->assertStatus(200);
        $response->assertViewIs('user.index');
        $response->assertViewHas('page_title', 'Pengguna');
        $response->assertViewHas('page_description', 'Daftar Data');
    }

    /**
     * Test create method displays user creation form.
     *
     * @return void
     */
    public function test_create_displays_user_creation_form()
    {
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $response = $this->actingAs($user)->get(route('setting.user.create'));

        $response->assertStatus(200);
        $response->assertViewIs('user.create');
        $response->assertViewHas('page_title', 'Pengguna');
        $response->assertViewHas('page_description', 'Tambah Data');
        $response->assertViewHas('item');
        $response->assertViewHas('pengurus');
    }

    /**
     * Test store method creates a new user successfully.
     *
     * @return void
     */
    public function test_store_creates_new_user_successfully()
    {
        Storage::fake('public');
        
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $pengurus = Pengurus::factory()->create();
        $role = Role::where('name', 'administrator-website')->first();
        
        $userData = [
            'name' => $this->faker->name,
            'email' => $this->faker->unique()->safeEmail,
            'password' => 'password@123OK',
            'password_confirmation' => 'password@123OK',
            'phone' => '08123456789',
            'address' => $this->faker->address,
            'pengurus_id' => $pengurus->id,
            'role' => [$role->name],
            'status' => 1,
        ];

        $response = $this->actingAs($user)->post(route('setting.user.store'), $userData);

        $response->assertRedirect(route('setting.user.index'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'email' => $userData['email'],
            'name' => $userData['name'],
        ]);
    }

    /**
     * Test store method fails with invalid data.
     *
     * @return void
     */
    public function test_store_fails_with_invalid_data()
    {
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $invalidData = [
            'name' => '',
            'email' => 'invalid-email',
            'password' => '123',
            'address' => '',
        ];

        $response = $this->actingAs($user)->post(route('setting.user.store'), $invalidData);

        $response->assertSessionHasErrors(['name', 'email', 'password', 'address']);
    }    

    /**
     * Test edit method displays edit form for super admin.
     *
     * @return void
     */
    public function test_edit_displays_form_for_super_admin()
    {
        $superAdmin = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();

        $response = $this->actingAs($superAdmin)->get(route('setting.user.edit', $targetUser->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $targetUser);
    }

    /**
     * Test edit method displays edit form for user editing own profile.
     *
     * @return void
     */
    public function test_edit_displays_form_for_user_editing_own_profile()
    {
        $user = User::first();

        $response = $this->actingAs($user)->get(route('setting.user.edit', $user->id));

        $response->assertStatus(200);
        $response->assertViewIs('user.edit');
        $response->assertViewHas('user', $user);
    }

    /**
     * Test update method updates user successfully for super admin.
     *
     * @return void
     */
    public function test_update_updates_user_successfully_for_super_admin()
    {
        $superAdmin = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $targetUser = User::where('id', '!=', $superAdmin->id)->first();
        $role = Role::where('name', 'administrator-website')->first();

        $updateData = [
            'name' => 'Updated Name',
            'email' => $this->faker->unique()->safeEmail,
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
            'email' => $updateData['email'],
        ]);
    }

    /**
     * Test update method updates user profile and redirects to dashboard for regular user.
     *
     * @return void
     */
    public function test_update_updates_profile_and_redirects_to_dashboard_for_regular_user()
    {
        $regularUser = User::whereHas('roles', function($query) {
            $query->where('name', '!=', 'super-admin');
        })->first();

        $updateData = [
            'name' => 'Updated Profile Name',
            'email' => $regularUser->email, // Same email to avoid unique constraint
            'phone' => '08987654321',
            'address' => 'Updated Profile Address',
        ];

        $response = $this->actingAs($regularUser)->put(route('setting.user.update', $regularUser->id), $updateData);

        $response->assertRedirect(route('dashboard'));
        $response->assertSessionHas('success');
        
        $this->assertDatabaseHas('users', [
            'id' => $regularUser->id,
            'name' => 'Updated Profile Name',
        ]);
    }
    
    /**
     * Test destroy method deactivates user successfully.
     *
     * @return void
     */
    public function test_destroy_deactivates_user_successfully()
    {
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $targetUser = User::where('id', '!=', $user->id)->first();
        $targetUser->status = 1;
        $targetUser->save();
        
        $response = $this->actingAs($user)->post(route('setting.user.destroy', $targetUser->id));

        $response->assertRedirect(route('setting.user.index'));        
        
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'status' => 0,
        ]);
    }

    /**
     * Test active method activates user successfully.
     *
     * @return void
     */
    public function test_active_activates_user_successfully()
    {
        $user = User::whereHas('roles', function($query) {
            $query->where('name', 'super-admin');
        })->first();
        
        $targetUser = User::where('id', '!=', $user->id)->first();
        $targetUser->status = 0;
        $targetUser->save();

        $response = $this->actingAs($user)->post(route('setting.user.active', $targetUser->id));

        $response->assertRedirect(route('setting.user.index'));        
        
        $this->assertDatabaseHas('users', [
            'id' => $targetUser->id,
            'status' => 1,
        ]);
    }

    /**
     * Test getDataUser method returns JSON response for DataTables.
     *
     * @return void
     */
    public function test_getDataUser_returns_json_response()
    {
        $user = User::first();
        
        $response = $this->actingAs($user)->get(route('setting.user.getdata'));

        $response->assertStatus(200);
        $response->assertJsonStructure([
            'data' => [
                '*' => [
                    'name',
                    'address',
                    'status',
                    'id',
                    'email',
                    'created_at',
                    'phone',
                    'telegram_id',
                    'otp_channel',
                    'otp_verified',
                    'aksi'
                ]
            ]
        ]);
    }

    /**
     * Create test roles for testing.
     *
     * @return void
     */
    private function createTestRoles()
    {
        Role::firstOrCreate(['name' => 'super-admin']);
        Role::firstOrCreate(['name' => 'administrator-website']);
        Role::firstOrCreate(['name' => 'user']);
    }

    /**
     * Create test users for testing.
     *
     * @return void
     */
    private function createTestUsers()
    {
        // Create super admin
        $superAdmin = User::factory()->create([
            'email' => 'superadmin@test.com',
            'password' => bcrypt('password'),
        ]);
        $superAdmin->assignRole('super-admin');

        // Create regular admin
        $admin = User::factory()->create([
            'email' => 'admin@test.com',
            'password' => bcrypt('password'),
        ]);
        $admin->assignRole('administrator-website');

        // Create regular user
        $user = User::factory()->create([
            'email' => 'user@test.com',
            'password' => bcrypt('password'),
        ]);
        $user->assignRole('user');
    }
}