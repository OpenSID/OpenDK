<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateMediaSosialTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('media_sosial', function (Blueprint $table) {
            $table->increments('id');
            $table->text('gambar');
            $table->text('link')->nullable()->default(null);
            $table->string('nama', 100);
            $table->boolean('tipe')->default(1); // 1: Personal; 2: Grup
            $table->boolean('status')->default(0); // 0: Tidak aktif; 1: Aktif
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('media_sosial');
    }
}
