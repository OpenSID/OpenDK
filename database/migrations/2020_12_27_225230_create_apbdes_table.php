<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateApbdesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_apbdes', function (Blueprint $table) {
            $table->increments('id');
            $table->string('nama', 100);
            $table->integer('tahun')->nullable();
            $table->integer('semester')->nullable();
            $table->string('nama_file', 255)->nullable(true);
            $table->string('mime_type', 4)->nullable(true);
            $table->dateTime('tgl_upload')->nullable(true);
            $table->dateTime('created_at')->nullable(true);
            $table->dateTime('updated_at')->nullable(true);
            $table->integer('id_apbdes')->nullable(true);
            $table->char('desa_id', 13)->nullable(true);
            $table->char('kecamatan_id', 8)->nullable(true);
            $table->char('kabupaten_id', 5)->nullable(true);
            $table->char('provinsi_id', 2)->nullable(true);
            $table->dateTime('imported_at')->nullable(true);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_apbdes');
    }
}
