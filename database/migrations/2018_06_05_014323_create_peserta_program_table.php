<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreatePesertaProgramTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_peserta_program', function (Blueprint $table) {
            $table->increments('id');
            $table->char('peserta',16);
            $table->integer('program_id');
            $table->tinyInteger('sasaran')->nullable()->unsigned();
            $table->string('no_id_kartu', 30)->nullable();
            $table->char('kartu_nik', 16)->nullable();
            $table->string('kartu_nama', 100)->nullable();
            $table->string('kartu_tempat_lahir', 100)->nullable();
            $table->date('kartu_tanggal_lahir')->nullable();
            $table->string('kartu_alamat', 200)->nullable();
            $table->string('kartu_peserta', 100)->nullable();
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
        Schema::dropIfExists('das_peserta_program');
    }
}
