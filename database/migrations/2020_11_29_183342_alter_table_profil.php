<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;
use Doctrine\DBAL\Types\StringType; use Doctrine\DBAL\Types\Type;


class AlterTableProfil extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Type::hasType('char')) {
            Type::addType('char', StringType::class);
        }
        Schema::table('das_profil', function (Blueprint $table) {
            $table->string('alamat', 200)->nullable(true)->change();
            $table->char('kode_pos', 12)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_profil', function (Blueprint $table) {
            $table->string('alamat', 200)->change();
            $table->char('kode_pos', 12)->change();
        });
    }
}
