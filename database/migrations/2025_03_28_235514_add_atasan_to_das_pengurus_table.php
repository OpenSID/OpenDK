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
        Schema::table('das_pengurus', function (Blueprint $table) {
            $table->unsignedBigInteger('atasan')->nullable()->after('bagan_warna');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_pengurus', function (Blueprint $table) {
            $table->dropColumn('atasan');
        });
    }
};
