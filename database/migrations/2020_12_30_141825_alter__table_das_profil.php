<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDasProfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_profil', function($table)
        {
            $table->longText('sambutan')->nullable();
            $table->json('socialmedia')->nullable();
            $table->longText('foto_kepala_wilayah')->nullable()
            ->after('misi');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
