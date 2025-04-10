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
        Schema::table('das_form_dokumen', function (Blueprint $table) {
            $table->unsignedInteger('jenis_dokumen_id')->nullable()->change();
            $table->foreign('jenis_dokumen_id')
                  ->references('id')
                  ->on('das_jenis_dokumen')
                  ->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_form_dokumen', function (Blueprint $table) {
            $table->dropForeign(['jenis_dokumen_id']);
        });
    }
};
