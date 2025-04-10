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
        Schema::table('das_pengurus', function (Blueprint $table) {
            $table->integer('bagan_tingkat')->nullable()->after('jabatan_id');
            $table->integer('bagan_offset')->nullable()->after('bagan_tingkat');
            $table->string('bagan_layout', 50)->nullable()->after('bagan_offset');
            $table->string('bagan_warna', 20)->nullable()->after('bagan_layout');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_pengurus', function (Blueprint $table) {
            $table->dropColumn(['bagan_tingkat', 'bagan_offset', 'bagan_layout', 'bagan_warna']);
        });
    }
};
