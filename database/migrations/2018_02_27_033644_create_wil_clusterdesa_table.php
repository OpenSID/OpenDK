<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWilClusterdesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_wil_clusterdesa', function (Blueprint $table) {
            $table->increments('id');
            $table->string('rt',10)->nullable();
            $table->string('rw',10)->nullable();
            $table->string('dusun',50)->nullable();
            $table->integer('id_kepala')->nullable();
            $table->string('lat',20)->nullable();
            $table->string('lng',20)->nullable();
            $table->integer('zoom')->nullable();
            $table->text('path')->nullable();
            $table->string('map_tipe',20)->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_wil_clusterdesa');
    }
}
