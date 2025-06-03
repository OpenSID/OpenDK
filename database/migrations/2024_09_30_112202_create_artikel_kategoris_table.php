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
        /*  
        id_kategori
        nama_kategori
        slug
        status
        tanggal_buat
        */
        Schema::create('das_artikel_kategori', function (Blueprint $table) {
            $table->id('id_kategori');
            $table->string('nama_kategori');
            $table->string('slug')->unique();
            $table->enum('status', ['Ya', 'Tidak'])->default('Ya');
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
        Schema::dropIfExists('das_artikel_kategori');
    }
};
