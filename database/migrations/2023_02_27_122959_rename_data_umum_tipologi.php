<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class RenameDataUmumTipologi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->renameColumn('tipologi', 'sejarah');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->renameColumn('sejarah', 'tipologi');
        });
    }
}
