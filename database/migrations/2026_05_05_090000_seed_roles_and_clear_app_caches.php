<?php

use Database\Seeders\RoleSpatieSeeder;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\Artisan;
use Spatie\Permission\PermissionRegistrar;

return new class extends Migration
{
    public function up(): void
    {
        (new RoleSpatieSeeder())->run();

        app(PermissionRegistrar::class)->forgetCachedPermissions();

        Artisan::call('optimize:clear');
    }

    public function down(): void
    {
        // Seeder role/permission bersifat idempotent, tidak di-rollback otomatis.
    }
};
