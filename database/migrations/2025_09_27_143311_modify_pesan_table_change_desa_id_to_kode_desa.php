<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Step 1: Drop foreign key constraint first
        Schema::table('das_pesan', function (Blueprint $table) {
            // Drop foreign key constraint
            $table->dropForeign('das_pesan_das_data_desa_id_foreign');            
        });

        Schema::table('das_pesan', function (Blueprint $table) {           
            $table->text('additional_info')->nullable();
            $table->string('das_data_desa_id', 20)->nullable()->change();            
        });

        // Step 2: Migrate data from ID to kode_desa
        DB::statement("
            UPDATE das_pesan p 
            JOIN das_data_desa d ON p.das_data_desa_id = d.id 
            SET p.das_data_desa_id = d.desa_id
        ");
        DB::statement("
            UPDATE das_pesan p
            JOIN das_data_desa d ON p.das_data_desa_id = d.desa_id
            SET p.additional_info = JSON_OBJECT('nama_desa', d.nama)
        ");        
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Migrate data back from kode_desa to ID first
        DB::statement("
            UPDATE das_pesan p 
            JOIN das_data_desa d ON p.das_data_desa_id = d.desa_id 
            SET p.das_data_desa_id = d.id
        ");
        
        Schema::table('das_pesan', function (Blueprint $table) {
            // Revert back to integer 
            $table->unsignedBigInteger('das_data_desa_id')->change();
            $table->dropColumn('additional_info'); 
            // Add foreign key constraint back
            $table->foreign('das_data_desa_id')->references('id')->on('das_data_desa');
        });
    }
};
