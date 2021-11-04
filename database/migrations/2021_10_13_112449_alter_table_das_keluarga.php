<?php

use Doctrine\DBAL\Types\Type;
use Doctrine\DBAL\Types\StringType;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AlterTableDasKeluarga extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $this->charType();
        Schema::table('das_keluarga', function (Blueprint $table) {
            $table->char('desa_id', 13)->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        $this->charType();
        Schema::table('das_keluarga', function (Blueprint $table) {
            $table->char('desa_id', 10)->nullable()->change();
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
