<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateDataDesaTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_data_desa', function (Blueprint $table) {
            $table->increments('id');
            $table->char('desa_id', 10)->nullable();
            $table->char('kecamatan_id', 7);
            $table->string('nama', 255);
            $table->string('website', 255)->nullable();
            $table->double('luas_wilayah')->nullable();
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
        Schema::dropIfExists('das_data_desa');
    }
}
