<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('das_lembaga', function (Blueprint $table) {
            $table->unsignedInteger('penduduk_id_gabungan')->nullable()->after('penduduk_id');
        });

        Schema::table('das_lembaga_anggota', function (Blueprint $table) {
            $table->unsignedInteger('penduduk_id_gabungan')->nullable()->after('penduduk_id');
            $table->dropForeign(['penduduk_id']);
            $table->unsignedInteger('penduduk_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('das_lembaga', function (Blueprint $table) {
            $table->dropColumn('penduduk_id_gabungan');
        });

        Schema::table('das_lembaga_anggota', function (Blueprint $table) {
            $table->unsignedInteger('penduduk_id')->nullable(false)->change();
            $table->foreign('penduduk_id')->references('id')->on('das_penduduk')->onDelete('cascade');
            $table->dropColumn('penduduk_id_gabungan');
        });
    }
};
