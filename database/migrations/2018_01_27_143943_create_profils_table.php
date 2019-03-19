<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateProfilsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_profil', function (Blueprint $table) {
            $table->increments('id');
            $table->char('provinsi_id', 2)->nullable(true);
            $table->char('kabupaten_id', 4)->nullable(true);
            $table->char('kecamatan_id', 7);
            $table->string('alamat', 200);
            $table->char('kode_pos', 12);
            $table->char('telepon', 15)->nullable(true);
            $table->string('email', 255)->nullable(true);
            $table->integer('tahun_pembentukan')->nullable(true);
            $table->string('dasar_pembentukan', 20)->nullable(true);
            $table->string('nama_camat', 150)->nullable(true);
            $table->string('sekretaris_camat', 150)->nullable(true);
            $table->string('kepsek_pemerintahan_umum', 150)->nullable(true);
            $table->string('kepsek_kesejahteraan_masyarakat', 150)->nullable(true);
            $table->string('kepsek_pemberdayaan_masyarakat', 150)->nullable(true);
            $table->string('kepsek_pelayanan_umum', 150)->nullable(true);
            $table->string('kepsek_trantib', 150)->nullable(true);
            $table->string('file_struktur_organisasi', 255)->nullable(true);
            $table->string('file_logo', 255)->nullable();
            $table->longText('visi')->nullable(true);
            $table->longText('misi')->nullable(true);
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
        Schema::dropIfExists('das_profil');
    }
}