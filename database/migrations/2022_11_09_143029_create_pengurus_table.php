<?php

use App\Models\Jabatan;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreatePengurusTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pengurus', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 255);
            $table->string('gelar_depan', 255)->nullable();
            $table->string('gelar_belakang', 255)->nullable();
            $table->bigInteger('nip')->nullable()->unique();
            $table->bigInteger('nik')->unique();
            $table->boolean('status')->default(1); // 0: Tidak Aktif; 1: aktif
            $table->string('foto', 255)->nullable();
            $table->string('tempat_lahir', 255);
            $table->date('tanggal_lahir');
            $table->boolean('sex');
            $table->unsignedInteger('pendidikan_id');
            $table->unsignedInteger('agama_id');
            $table->string('no_sk', 255)->nullable();
            $table->date('tanggal_sk')->nullable();
            $table->string('masa_jabatan', 255);
            $table->string('pangkat', 255)->nullable();
            $table->string('no_henti', 255)->nullable();
            $table->date('tanggal_henti')->nullable();
            $table->unsignedInteger('jabatan_id');
            $table->foreign('jabatan_id')->references('id')->on('ref_jabatan')
                ->onDelete('restrict')->onUpdate('cascade');
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
        Schema::dropIfExists('das_pengurus');
    }
}
