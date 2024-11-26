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
        Schema::create('das_lembaga_anggota', function (Blueprint $table) {
            $table->id();  // ID otomatis menggunakan BIGINT
            $table->unsignedBigInteger('lembaga_id');  // Tipe BIGINT untuk lembaga
            $table->unsignedInteger('penduduk_id');   // Tipe INT untuk penduduk
            // Foreign key untuk lembaga_id dan penduduk_id
            $table->foreign('lembaga_id')->references('id')->on('das_lembaga')->onDelete('cascade');
            $table->foreign('penduduk_id')->references('id')->on('das_penduduk')->onDelete('cascade');
            $table->string('no_anggota')->nullable();
            $table->string('jabatan')->nullable();
            $table->string('no_sk_jabatan')->nullable();
            $table->string('periode')->nullable();
            $table->string('no_sk_pengangkatan')->nullable();
            $table->date('tgl_sk_pengangkatan')->nullable();
            $table->string('no_sk_pemberhentian')->nullable();
            $table->date('tgl_sk_pemberhentian')->nullable();
            $table->text('keterangan')->nullable();
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
        Schema::dropIfExists('das_lembaga_anggota');
    }
};
