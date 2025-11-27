<?php

use Database\Seeders\DefautTenantTable;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{    

    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Add tenant_id to core tables that need multi-tenant isolation if they exist
        // This approach checks for table existence before adding columns

        $tables = DefautTenantTable::HAS_TENANT_COLUMN;
        $defaultTenantId = \Illuminate\Support\Facades\DB::table('tenants')->min('id');
        foreach ($tables as $table) {
            if (!Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->unsignedBigInteger('tenant_id')->nullable();
                    $blueprint->foreign('tenant_id')->on('tenants')->references('id')->onDelete('cascade')->onUpdate('cascade');
                });

                // Update existing records to set tenant_id to default tenant
                if ($defaultTenantId) {
                    \Illuminate\Support\Facades\DB::table($table)->update(['tenant_id' => $defaultTenantId]);
                }
            }
            
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Remove tenant_id columns from tables if they exist
        $tables = DefautTenantTable::HAS_TENANT_COLUMN;

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {
                    $blueprint->dropForeign('tenant_id');
                    $blueprint->dropColumn('tenant_id');
                });
            }
        }        
    }
};
