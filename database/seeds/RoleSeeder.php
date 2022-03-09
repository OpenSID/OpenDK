<?php

use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $admin = Role::create([
            'name' => 'super admin',
            'guard_name' => 'super-admin'
        ]);

        $desa = Role::create([
            'name' => 'admin desa',
            'guard_name' => 'admin-desa'
        ]);

        $kecamatan = Role::create([
            'name' => 'admin kecamatan',
            'guard_name' => 'admin-kecamatan'
        ]);

        $puskesmas = Role::create([
            'name' => 'admin puskesmas',
            'guard_name' => 'admin-puskesmas'
        ]);

        $pendidikan = Role::create([
            'name' => 'admin pendidikan',
            'guard_name' => 'admin-pendidikan'
        ]);

        $komplain = Role::create([
            'name' => 'admin komplain',
            'guard_name' => 'admin-komplain'
        ]);

        $website = Role::create([
            'name' => 'administrator website',
            'guard_name' => 'administrator-website'
        ]);

        $read =  Role::create([
            'name' => 'read',
            'guard_name' => 'read'
        ]);

       $writer =  Role::create([
            'name' => 'write',
            'guard_name' => 'write'
        ]);

        $edit = Role::create([
            'name' => 'edit',
            'guard_name' => 'edit'
        ]);

        $hapus = Role::create([
            'name' => 'hapus',
            'guard_name' => 'hapus'
        ]);

        $admin->givePermissionTo([$read, $writer, $edit, $hapus]);
        $writer->givePermissionTo([$read, $writer, $edit, $hapus]);
        $desa->givePermissionTo([$read, $writer, $edit, $hapus]);
        $kecamatan->givePermissionTo([$read, $writer, $edit, $hapus]);
        $puskesmas->givePermissionTo([$read, $writer, $edit, $hapus]);
        $pendidikan->givePermissionTo([$read, $writer, $edit, $hapus]);
        $komplain->givePermissionTo([$read, $writer, $edit, $hapus]);
        $website->givePermissionTo([$read, $writer, $edit, $hapus]);
    }
}
