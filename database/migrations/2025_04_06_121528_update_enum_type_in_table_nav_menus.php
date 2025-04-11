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
            DB::statement("ALTER TABLE nav_menus MODIFY COLUMN type ENUM('link', 'halaman', 'kategori', 'modul', 'dokumen') NOT NULL DEFAULT 'modul'");
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
            DB::statement("ALTER TABLE nav_menus MODIFY COLUMN type ENUM('link', 'halaman', 'kategori', 'modul') NOT NULL DEFAULT 'modul'");
        });
    }
};
