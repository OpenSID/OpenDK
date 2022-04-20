<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddKolomDaspesertabantuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_peserta_program', function (Blueprint $table) {
            $table->Char('desa_id', 13)->after('kartu_peserta')->nullable(false);
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
            $table->dropColumn('desa_id');
        });
    }
}
