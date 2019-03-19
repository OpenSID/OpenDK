<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProsesKkTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_proses_kk', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('penduduk_id');
            $table->string('alamat', 255);
            $table->date('tanggal_pengajuan');
            $table->date('tanggal_selesai')->nullable(true);
            $table->string('status', 20);
            $table->string('catatan', 255)->nullable(true);
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
        Schema::dropIfExists('das_proses_kk');
    }
}
