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
        Schema::create('das_ppid_dokumen', function (Blueprint $table) {
            $table->id();
            $table->string('judul', 255);
            $table->unsignedBigInteger('jenis_dokumen_id');
            $table->enum('tipe_dokumen', ['file', 'url'])->default('file');
            $table->string('file_path', 255)->nullable();
            $table->string('url', 255)->nullable();
            $table->text('ringkasan')->nullable();
            $table->enum('status', ['terbit', 'tidak_terbit'])->default('terbit');
            $table->date('tanggal_publikasi')->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);

            $table->foreign('jenis_dokumen_id')->references('id')->on('das_ppid_jenis_dokumen');

            // Indexes for filtering
            $table->index('jenis_dokumen_id');
            $table->index('status');
            $table->index('tipe_dokumen');
            $table->index('tanggal_publikasi');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_ppid_dokumen');
    }
};
