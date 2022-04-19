<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTableDasPembangunan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pembangunan', function (Blueprint $table) {
            $table->integer('id', false);
            $table->char('kode_desa', 13);
            $table->string('lokasi', 255)->nullable();
            $table->string('sumber_dana', 255)->nullable();
            $table->string('judul', 255)->nullable();
            $table->string('slug', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->string('volume', 255)->nullable();
            $table->year('tahun_anggaran')->nullable();
            $table->string('pelaksana_kegiatan', 255)->nullable();
            $table->tinyInteger('status')->nullable();
            $table->string('foto', 255)->nullable();
            $table->float('anggaran', 65, 2)->nullable();
            $table->float('perubahan_anggaran', 65, 2)->nullable();
            $table->float('sumber_biaya_pemerintah', 65, 2)->nullable();
            $table->float('sumber_biaya_provinsi', 65, 2)->nullable();
            $table->float('sumber_biaya_kab_kota', 65, 2)->nullable();
            $table->float('sumber_biaya_swadaya', 65, 2)->nullable();
            $table->float('sumber_biaya_jumlah', 65, 2)->nullable();
            $table->string('manfaat', 100)->nullable();
            $table->integer('waktu')->nullable();
            $table->timestamps();
            $table->unique(['id', 'kode_desa']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_pembangunan');
    }
}
