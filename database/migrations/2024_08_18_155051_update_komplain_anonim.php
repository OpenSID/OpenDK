<?php

use App\Enums\Anonim;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_komplain', function (Blueprint $table) {
            $table->dropColumn('anonim');
        });

        Schema::table('das_komplain', function (Blueprint $table) {
            $table->tinyInteger('anonim')->default(Anonim::Sembunyikan);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_komplain', function (Blueprint $table) {
            $table->dropColumn('anonim');
        });

        Schema::table('das_komplain', function (Blueprint $table) {
            $table->tinyInteger('anonim')->nullable()->default(NULL);
        });
    }
};
