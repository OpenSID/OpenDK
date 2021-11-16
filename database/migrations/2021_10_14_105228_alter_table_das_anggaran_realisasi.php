<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDasAnggaranRealisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_anggaran_realisasi', function (Blueprint $table) {
            $table->dropColumn('kecamatan_id');
            $table->integer('profil_id')->after('id')->nullable()->default(1);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_anggaran_realisasi', function (Blueprint $table) {
            $table->char('kecamatan_id', 8);
            $table->dropColumn('profil_id');
        });
    }
}
