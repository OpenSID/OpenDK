<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('documents', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('das_penduduk_id')->constrained('das_penduduk')->onDelete('cascade');
            $table->unsignedBigInteger('pengurus_id')->constrained('users')->onDelete('cascade');
            $table->string('judul_document');
            $table->text('path_document');
            $table->string('kode_surat');
            $table->string('no_urut');
            $table->string('jenis_surat');
            $table->text('keterangan');
            $table->string('ditandatangani');
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
        Schema::dropIfExists('documents');
    }
};
