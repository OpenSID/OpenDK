<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_data_umum', function (Blueprint $table) {
            $table->string('lng', 20)->after('path')->nullable();
            $table->string('lat', 20)->after('path')->nullable();            
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
            $table->dropColumn('lng');
            $table->dropColumn('lat');
        });
    }
};
