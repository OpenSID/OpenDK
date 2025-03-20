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
        Schema::table('das_komplain', function (Blueprint $table) {
            if (!Schema::hasColumn('das_komplain', 'detail_penduduk')) {
                $table->text('detail_penduduk')->nullable()->after('nama');
            }
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
            if (Schema::hasColumn('das_komplain', 'detail_penduduk')) {
                $table->dropColumn('detail_penduduk');
            }
        });
    }
};
