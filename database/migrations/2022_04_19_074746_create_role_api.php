<?php

use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Schema;
use Spatie\Permission\Models\Permission;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRoleApi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         // create permissions
        Permission::create(['name' => 'view', 'guard_name' => 'api']);
        Permission::create(['name' => 'create', 'guard_name' => 'api']);
        Permission::create(['name' => 'edit', 'guard_name' => 'api']);
        Permission::create(['name' => 'delete', 'guard_name' => 'api']);

        $role = [
            ['name' =>'admin-desa', 'guard_name' => 'api'],
            ['name' =>'admin-kecamatan', 'guard_name' => 'api'],
            ['name' =>'admin-puskesmas', 'guard_name' => 'api'],
            ['name' =>'admin-pendidikan', 'guard_name' => 'api'],
            ['name' =>'admin-komplain', 'guard_name' => 'api'],
            ['name' =>'administrator-website', 'guard_name' => 'api'],
        ];
        foreach ($role as $value) {
            Role::create($value)->givePermissionTo(['view', 'create', 'edit', 'delete']);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
         
    }
}
