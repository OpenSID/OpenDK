<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateWilayahsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('ref_wilayah', function (Blueprint $table) {
            $table->string('kode', 13)->nullable(false)->primary();
            $table->string('nama', 100)->nullable(false);
            $table->char('tahun', 4)->nullable(false)->default('2018');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('ref_wilayah');
    }
}
