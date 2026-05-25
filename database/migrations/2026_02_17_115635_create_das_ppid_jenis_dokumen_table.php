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
        Schema::create('das_ppid_jenis_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('slug', 255)->unique();
            $table->string('nama', 255);
            $table->integer('urutan')->default(0);
            $table->enum('status', ['aktif', 'tidak_aktif'])->default('aktif');
            $table->boolean('is_kunci')->default(false);
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            // Indexes
            $table->index('status');
            $table->index('urutan');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_ppid_jenis_dokumen');
    }
};
