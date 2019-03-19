<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePotensiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_potensi', function (Blueprint $table) {
            $table->increments('id');
            $table->integer('kategori_id');
            $table->string('nama_potensi', 200);
            $table->text('deskripsi');
            $table->string('lokasi', 200);
            $table->string('file_gambar', 255)->nullable();
            $table->decimal('long',15,12)->nullable();
            $table->decimal('lat',15,12)->nullable();
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
        Schema::dropIfExists('das_potensi');
    }
}
