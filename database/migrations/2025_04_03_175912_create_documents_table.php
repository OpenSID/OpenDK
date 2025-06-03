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
            $table->unsignedBigInteger('pengurus_id')->constrained('users')->onDelete('cascade');
            $table->string('judul_document');
            $table->text('path_document');
            $table->text('nama_document');
            $table->string('kode_surat');
            $table->integer('no_urut');
            $table->unsignedBigInteger('jenis_surat')->constrained('das_jenis_surat')->onDelete('cascade');
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
