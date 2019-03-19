<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateFailitasPaudTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_fasilitas_paud', function (Blueprint $table) {
            $table->increments('id');
            $table->char('kecamatan_id', 7);
            $table->char('desa_id', 10);
            $table->integer('semester');
            $table->integer('tahun');
            $table->integer('jumlah_paud');
            $table->integer('jumlah_guru_paud');
            $table->integer('jumlah_siswa_paud');
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
        Schema::dropIfExists('das_fasilitas_paud');
    }
}
