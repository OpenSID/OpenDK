<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFieldSumberLuasWilayah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->integer('sumber_luas_wilayah',null,1,)->before('luas_wilayah')->default(1)->comment('1:Input Manual,2:Dari Luas Desa');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_data_desa', function (Blueprint $table) {
            $table->dropColumn('sumber_luas_wilayah');
        });
    }
}
