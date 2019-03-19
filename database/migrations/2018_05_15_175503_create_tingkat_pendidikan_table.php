<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateTingkatPendidikanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_tingkat_pendidikan', function (Blueprint $table) {
            $table->increments('id');
            $table->char('kecamatan_id', 7);
            $table->char('desa_id', 10);
            $table->integer('semester');
            $table->integer('tahun');
            $table->integer('tidak_tamat_sekolah')->default(0);
            $table->integer('tamat_sd')->default(0);
            $table->integer('tamat_smp')->default(0);
            $table->integer('tamat_sma')->default(0);
            $table->integer('tamat_diploma_sederajat')->default(0);
            $table->integer('import_id');
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
        Schema::dropIfExists('das_tingkat_pendidikan');
    }
}
