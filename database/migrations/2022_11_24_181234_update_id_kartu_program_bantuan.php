<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class UpdateIdKartuProgramBantuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_peserta_program', function (Blueprint $table) {
            $table->string('no_id_kartu', 100)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_peserta_program', function (Blueprint $table) {
            $table->string('no_id_kartu', 30)->nullable()->change();
        });
    }
}
