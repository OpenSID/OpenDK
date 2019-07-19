<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AlterTableAnggaranRealisasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_anggaran_realisasi', function (Blueprint $table) {
            $table->string('kecamatan_id', 8)->change();
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
            $table->string('kecamatan_id', 7)->change();
        });
    }
}
