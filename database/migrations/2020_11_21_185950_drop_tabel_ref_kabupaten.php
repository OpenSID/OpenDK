<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropTabelRefKabupaten extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('ref_kabupaten');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::create('ref_kabupaten', function (Blueprint $table) {
            $table->char('id', 4);
            $table->char('provinsi_id', 2);
            $table->string('nama', 255);
        });
    }
}
