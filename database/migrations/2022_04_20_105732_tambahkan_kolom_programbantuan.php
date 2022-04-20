<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class TambahkanKolomProgrambantuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_program', function (Blueprint $table) {
            $table->Char('desa_id', 13)->after('sasaran')->nullable();
            $table->tinyInteger('status', false, 1)->after('sasaran')->nullable();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_program', function (Blueprint $table) {
            $table->dropColumn('desa_id');
            $table->dropColumn('status');
        });
    }
}
