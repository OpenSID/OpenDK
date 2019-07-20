<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddFieldsPendudukTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // add id_pend_desa (id pada tweb_penduduk di OpenSID desa)
        Schema::table('das_penduduk', function(Blueprint $table) {
            $table->integer('id_pend_desa')->nullable(true);
        });
        Schema::table('das_penduduk', function(Blueprint $table) {
            $table->dateTime('imported_at')->nullable(true);
        });
        // ubah NIK supaya tidak harus unik, karena NIK mungkin 0, kalau belum ada
        DB::statement("ALTER TABLE das_penduduk DROP INDEX das_penduduk_nik_unique");
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_penduduk', function($table) {
            $table->dropColumn('id_pend_desa');
        });
        Schema::table('das_penduduk', function(Blueprint $table) {
            $table->dropColumn('imported_at');
        });
        DB::statement("ALTER TABLE das_penduduk ADD CONSTRAINT das_penduduk_nik_unique UNIQUE (nik)");
    }
}
