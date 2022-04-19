<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDasPembangunanDokumentasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pembangunan_dokumentasi', function (Blueprint $table) {
            $table->integer('id');
            $table->integer('id_pembangunan');
            $table->char('kode_desa', 13);
            $table->string('gambar', 255)->nullable();
            $table->string('persentase', 255)->nullable();
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            $table->unique(['id', 'kode_desa', 'id_pembangunan']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('table_das_pembangunan_dokumentasi');
    }
}
