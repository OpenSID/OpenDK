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
        Schema::create('das_lembaga', function (Blueprint $table) {
            $table->id();  // ID otomatis menggunakan BIGINT
            $table->foreignId('lembaga_kategori_id')->constrained('das_lembaga_kategori')->onDelete('cascade')->nullable();
            $table->unsignedInteger('penduduk_id')->nullable()->constrained('das_penduduk')->onDelete('set null'); // Penduduk menggunakan INT
            $table->string('nama');
            $table->string('slug')->unique();
            $table->text('keterangan')->nullable();
            $table->string('kode')->nullable()->unique();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_lembaga');
    }
};
