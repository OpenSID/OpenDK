<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDashPenduduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_penduduk', function (Blueprint $table) {
            $table->dropColumn('kecamatan_id');
            $table->dropColumn('kabupaten_id');
            $table->dropColumn('provinsi_id');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_penduduk', function (Blueprint $table) {
            $table->char('kecamatan_id', 8)->nullable(true);
            $table->char('kabupaten_id', 5)->nullable(true);            
            $table->char('provinsi_id', 2)->nullable(true);
        });

        // Isi data
        if ($profil = Profil::first()) {
            Penduduk::update([
                'kecamatan_id' => $profil->kecamatan_id,
                'kabupaten_id' => $profil->kabupaten_id,
                'provinsi_id' => $profil->provinsi_id,
            ]);
        }
    }
}
