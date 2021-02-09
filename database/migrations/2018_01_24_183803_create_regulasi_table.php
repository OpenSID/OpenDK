<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateRegulasiTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_regulasi', function (Blueprint $table) {
            $table->increments('id');
            $table->char('kecamatan_id',7);
            $table->string('tipe_regulasi', 30);
            $table->string('judul', 200);
            $table->text('deskripsi');
            $table->string('file_regulasi', 255);
            $table->string('mime_type', 20);
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
        Schema::dropIfExists('das_regulasi');
    }
}
