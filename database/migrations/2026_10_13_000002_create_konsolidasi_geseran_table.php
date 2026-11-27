<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('konsolidasi_geseran', function (Blueprint $table) {
            $table->char('desa_id', 13)->primary();
            $table->unsignedBigInteger('offset');
            $table->json('jumlah_baris');
            $table->timestamp('diterapkan_pada')->useCurrent();
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('konsolidasi_geseran');
    }
};
