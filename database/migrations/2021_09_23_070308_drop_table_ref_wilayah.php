<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTableRefWilayah extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ref_wilayah');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('ref_wilayah', function (Blueprint $table) {
            $table->string('kode', 13)->nullable(false)->primary();
            $table->string('nama', 100)->nullable(false);
            $table->char('tahun', 4)->nullable(false)->default('2018');
        });
    }
}
