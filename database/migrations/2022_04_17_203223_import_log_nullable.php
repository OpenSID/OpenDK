<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class ImportLogNullable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_tingkat_pendidikan', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->integer('import_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_tingkat_pendidikan', function (Blueprint $table) {
            // change() tells the Schema builder that we are altering a table
            $table->integer('import_id')->nullable(false)->change();
        });
    }
}
