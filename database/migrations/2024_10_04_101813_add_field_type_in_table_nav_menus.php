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
        Schema::table('nav_menus', function (Blueprint $table) {
            $table->enum('type', ['link', 'halaman', 'kategori', 'modul'])->default('modul')->after('target');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('nav_menus', function (Blueprint $table) {
            //
        });
    }
};
