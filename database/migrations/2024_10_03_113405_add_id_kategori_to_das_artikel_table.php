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
        Schema::table('das_artikel', function (Blueprint $table) {
            // Menambahkan kolom id_kategori dengan tipe unsignedBigInteger dan nullable
            $table->unsignedBigInteger('id_kategori')->nullable()->after('id');

            // Menambahkan foreign key constraint
            $table->foreign('id_kategori')->references('id_kategori')->on('das_artikel_kategori')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_artikel', function (Blueprint $table) {
            // Drop foreign key constraint terlebih dahulu sebelum menghapus kolom
            $table->dropForeign(['id_kategori']);

            // Menghapus kolom id_kategori
            $table->dropColumn('id_kategori');
        });
    }
};
