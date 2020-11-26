<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTabelRefDesa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ref_desa');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('ref_desa', function (Blueprint $table) {
            $table->char('id', 10);
            $table->char('kecamatan_id', 7);
            $table->string('nama', 255);
        });
    }
}
