<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Menambahkan constraint unique pada kombinasi kolom nama dan tenant_id
        Schema::table('das_penduduk_sex', function (Blueprint $table) {                        
            // Tambahkan unique constraint pada kombinasi nama dan tenant_id
            $table->unique(['nama', 'tenant_id'], 'das_penduduk_sex_nama_tenant_id_unique');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_penduduk_sex', function (Blueprint $table) {
            // Hapus unique constraint
            $table->dropUnique('das_penduduk_sex_nama_tenant_id_unique');            
        });
    }
};