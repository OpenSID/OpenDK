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
        Schema::create('ppid_jenis_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('nama', 150);
            $table->string('slug', 150)->unique();
            $table->text('deskripsi')->nullable();
            $table->string('kode', 50)->nullable();
            $table->string('icon', 100)->nullable();
            $table->integer('urut')->default(0);
            $table->enum('status', ['0', '1'])->default('1')->comment('0=tidak aktif, 1=aktif');
            $table->timestamps();
            $table->softDeletes();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('ppid_jenis_dokumen');
    }
};
