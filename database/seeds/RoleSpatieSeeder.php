<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Permission;
use Spatie\Permission\Models\Role;

class RoleSpatieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::statement("SET foreign_key_checks=0");
        DB::table('roles')->truncate();
        DB::table('permissions')->truncate();
        DB::table('role_has_permissions')->truncate();
        DB::table('model_has_roles')->truncate();
        DB::statement("SET foreign_key_checks=1");

        // DB::table('roles')->truncate();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

        // create permissions
        Permission::create(['name' => 'view', 'guard_name' => 'web']);
        Permission::create(['name' => 'create', 'guard_name' => 'web']);
        Permission::create(['name' => 'edit', 'guard_name' => 'web']);
        Permission::create(['name' => 'delete', 'guard_name' => 'web']);

        $role_admin =   Role::create(['name' =>'super-admin', 'guard_name' => 'web'])->givePermissionTo(['view', 'create', 'edit', 'delete']);
        // cek user admin
        $user = User::where('email', 'admin@mail.com')->first();

        if ($user === null) {
            $admin = User::create([
                'email' => 'admin@mail.com',
                'name' => 'Administrator',
                'gender' => 'Male',
                'address' => 'Jakarta',
                'status' => 1,
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole($role_admin);
        } else {
            $user->assignRole($role_admin);
        }

        $role = [
            ['name' =>'admin-desa', 'guard_name' => 'web'],
            ['name' =>'admin-kecamatan', 'guard_name' => 'web'],
            ['name' =>'admin-puskesmas', 'guard_name' => 'web'],
            ['name' =>'admin-pendidikan', 'guard_name' => 'web'],
            ['name' =>'admin-komplain', 'guard_name' => 'web'],
            ['name' =>'administrator-website', 'guard_name' => 'web'],
        ];
        foreach ($role as $value) {
            Role::create($value)->givePermissionTo(['view', 'create', 'edit', 'delete']);
        }
    }
}
