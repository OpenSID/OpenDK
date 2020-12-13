<?php

use Doctrine\DBAL\Types\StringType;
use Doctrine\DBAL\Types\Type;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDasPenduduk extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->charType();
        Schema::table('das_penduduk', function (Blueprint $table) {
            $table->char('id_rtm', 30)->nullable(true)->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('log_imports', function (Blueprint $table) {
            $table->integer('id_rtm')->nullable(true)->change();
        });
    }

    /**
     * Doctrine bug: [L5.1] Migrations - Cannot change an existing column to type char #9636.
     *
     * @see https://github.com/laravel/framework/issues/9636
     */
    protected function charType()
    {
        if (! Type::hasType('char')) {
            Type::addType('char', StringType::class);
        }
    }
}
