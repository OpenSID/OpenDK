<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSinergiProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_sinergi_program', function (Blueprint $table) {
            $table->increments('id');
            $table->string('gambar', 255);
            $table->text('url');
            $table->string('nama', 100);
            $table->boolean('status')->default(1); // 0: Tidak aktif; 1: Aktif
            $table->integer('urutan');
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
        Schema::dropIfExists('das_sinergi_program');
    }
}
