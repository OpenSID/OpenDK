<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateSuplemenTerdataTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_suplemen_terdata', function (Blueprint $table) {
            $table->increments('id');
            $table->unsignedInteger('suplemen_id');
            $table->foreign('suplemen_id')->references('id')->on('das_suplemen')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->unsignedInteger('penduduk_id');
            $table->foreign('penduduk_id')->references('id')->on('das_penduduk')
                ->onUpdate('cascade')->onDelete('cascade');
            $table->text('keterangan')->nullable();
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('das_suplemen_terdata');
        Schema::enableForeignKeyConstraints();
    }
}
