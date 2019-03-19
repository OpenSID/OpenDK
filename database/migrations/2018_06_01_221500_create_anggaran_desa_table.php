<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateAnggaranDesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_anggaran_desa', function (Blueprint $table) {
            $table->increments('id');
            $table->char('desa_id', 15);
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('no_akun', 10);
            $table->string('nama_akun', 255);
            $table->double('jumlah',16,2);
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
        Schema::dropIfExists('das_anggaran_desa');
    }
}
