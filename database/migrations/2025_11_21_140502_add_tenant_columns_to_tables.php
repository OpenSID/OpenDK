<?php

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

        $tables = [
            'das_profil',
            'das_data_umum',
            'das_data_desa',
            'users',
            'das_pengurus',
            'das_artikel',
            'das_setting',
            'das_penduduk',
            'das_keluarga',
            'suplemen',
            'komplain',
            'potensi',
            'slides',
            'albums',
            'faq',
            'events',
            'pesan',
            'surat',
            'regulasi',
            'program',
            'anggaran_realisasi',
            'apbdes',
            'imunisasi',
            'aki_akb',
            'pendidikan',
            'putus_sekolah',
            'pembangunan',
            'coa',
        ];        

        // Create tenant management table
        if (!Schema::hasTable('tenants')) {
            Schema::create('tenants', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('kecamatan_code')->unique(); // Using kecamatan code for identification
                $table->string('offset_range_start')->nullable(); // Starting offset for ID range
                $table->string('offset_range_end')->nullable(); // Ending offset for ID range
                $table->text('description')->nullable();
                $table->boolean('active')->default(true);
                $table->timestamps();
            });

            foreach ($tables as $table) {
                if (Schema::hasTable($table) && !Schema::hasColumn($table, 'tenant_id')) {
                    Schema::table($table, function (Blueprint $blueprint) use ($table) {
                        $blueprint->unsignedBigInteger('tenant_id')->nullable();
                        $blueprint->foreignId('tenant_id')->on('tenants')->references('id')->onDelete('cascade')->onUpdate('cascade');
                    });
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
        $tables = [
            'das_profil',
            'das_data_umum',
            'das_data_desa',
            'users',
            'das_pengurus',
            'das_artikel',
            'das_setting',
            'das_penduduk',
            'das_keluarga',
            'suplemen',
            'komplain',
            'potensi',
            'slides',
            'albums',
            'faq',
            'events',
            'pesan',
            'surat',
            'regulasi',
            'program',
            'anggaran_realisasi',
            'apbdes',
            'imunisasi',
            'aki_akb',
            'pendidikan',
            'putus_sekolah',
            'pembangunan',
            'coa',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'tenant_id')) {
                Schema::table($table, function (Blueprint $blueprint) use ($table) {                    
                    $blueprint->dropForeign('tenant_id');
                    $blueprint->dropColumn('tenant_id');

                });
            }
        }

        // Drop the tenants table
        Schema::dropIfExists('tenants');
    }
};
