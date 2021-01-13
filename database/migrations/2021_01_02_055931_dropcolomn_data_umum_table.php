<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropcolomnDataUmumTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->dropColumn(['jumlah_penduduk', 'jml_laki_laki', 'jml_perempuan', 'kepadatan_penduduk']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->integer('jumlah_penduduk')->nullable(true);
            $table->integer('jml_laki_laki')->nullable(true);
            $table->integer('jml_perempuan')->nullable(true);
            $table->integer('kepadatan_penduduk')->nullable(true);
        });
    }
}
