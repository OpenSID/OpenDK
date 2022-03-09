<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;
use App\Models\User;

class RoleSpatieSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        // DB::table('roles')->truncate();
        app()[\Spatie\Permission\PermissionRegistrar::class]->forgetCachedPermissions();

      

        // // create permissions
        // Permission::create(['name' => 'view', 'guard_name' => 'web']);
        // Permission::create(['name' => 'create', 'guard_name' => 'web']);
        // Permission::create(['name' => 'edit', 'guard_name' => 'web']);
        // Permission::create(['name' => 'delete', 'guard_name' => 'web']);

        // $role = [
        //     ['name' =>'super_admin', 'guard_name' => 'web'],
        //     ['name' =>'admin-puskesmas', 'guard_name' => 'web'],
        //     ['name' =>'admin-pendidikan', 'guard_name' => 'web'],
        //     ['name' =>'admin-komplain', 'guard_name' => 'web'],
        //     ['name' =>'administrator-website', 'guard_name' => 'web']
        // ];
        // foreach ($role as $value) {
        //     Role::create($value)->givePermissionTo(['view', 'create', 'edit', 'delete']);
        // }

        // cek user
        $user = User::where('email', 'admin')->first();
        if ($user === null) {
            $admin = User::create([
                'email' => 'admin@mail.com',
                'name' => 'Administrator',
                'gender' => 'Male',
                'address' => 'Jakarta',
                'phone'   => '622157905788',
                'status' => 1,
                'password' => bcrypt('password'),
            ]);
            $admin->assignRole('super_admin');
           
        }else{
            $user->assignRole('super_admin');
        }


       


      


    }
}
