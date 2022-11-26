<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Storage;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class DeletePerangkatProfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_profil', function($table) {
            $table->dropColumn('nama_camat');
            $table->dropColumn('sekretaris_camat');
            $table->dropColumn('kepsek_pemerintahan_umum');
            $table->dropColumn('kepsek_kesejahteraan_masyarakat');
            $table->dropColumn('kepsek_pemberdayaan_masyarakat');
            $table->dropColumn('kepsek_pelayanan_umum');
            $table->dropColumn('kepsek_trantib');
            $table->dropColumn('foto_kepala_wilayah');
        });

        Storage::deleteDirectory('public/profil/pegawai');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_profil', function ($table) {
            $table->string('nama_camat', 150)->nullable()->after('dasar_pembentukan');
            $table->string('sekretaris_camat', 150)->nullable()->after('nama_camat');
            $table->string('kepsek_pemerintahan_umum', 150)->nullable()->after('sekretaris_camat');
            $table->string('kepsek_kesejahteraan_masyarakat', 150)->nullable()->after('kepsek_pemerintahan_umum');
            $table->string('kepsek_pemberdayaan_masyarakat', 150)->nullable()->after('kepsek_kesejahteraan_masyarakat');
            $table->string('kepsek_pelayanan_umum', 150)->nullable()->after('kepsek_pemberdayaan_masyarakat');
            $table->string('kepsek_trantib', 150)->nullable()->after('kepsek_pelayanan_umum');
            $table->longText('foto_kepala_wilayah')->nullable()->after('misi');
        });
    }
}
