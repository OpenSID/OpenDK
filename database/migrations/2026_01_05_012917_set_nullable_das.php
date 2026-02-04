<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('das_profil', function (Blueprint $table) {
            $table->string('kabupaten_id', 5)->nullable()->change();
            $table->string('dasar_pembentukan', 50)->nullable()->change();
        });

        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->longText('tipologi')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // tidak perlu dikembalikan
    }
};
