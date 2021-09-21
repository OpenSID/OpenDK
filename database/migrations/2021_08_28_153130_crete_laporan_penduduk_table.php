<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreteLaporanPendudukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_laporan_penduduk', function (Blueprint $table) {
            $table->increments('id');
            $table->string('judul', 100);
            $table->integer('bulan');
            $table->integer('tahun');
            $table->string('nama_file', 255);
            $table->integer('id_laporan_penduduk');
            $table->char('desa_id', 13);
            $table->dateTime('imported_at')->nullable(true);
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
        Schema::dropIfExists('das_laporan_penduduk');

    }
}
