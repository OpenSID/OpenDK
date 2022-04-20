<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterProgrambantuan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_program', function (Blueprint $table) {
            $table->text('description')->nullable()->change();
            $table->integer('id')->nullable(false)->unsigned()->change();
            $table->dropPrimary('id');
            $table->unique(['id', 'desa_id']);
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
            $table->string('description', 200)->nullable()->change();
            $table->integer('id', true)->nullable(false)->change();
        });
    }
}
