<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePutusSekolahTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_putus_sekolah', function (Blueprint $table) {
            $table->increments('id');
            $table->char('kecamatan_id', 7);
            $table->char('desa_id', 10);
            $table->integer('semester');
            $table->integer('tahun');
            $table->integer('siswa_paud');
            $table->integer('anak_usia_paud');
            $table->integer('siswa_sd');
            $table->integer('anak_usia_sd');
            $table->integer('siswa_smp');
            $table->integer('anak_usia_smp');
            $table->integer('siswa_sma');
            $table->integer('anak_usia_sma');
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
        Schema::dropIfExists('das_putus_sekolah');
    }
}
