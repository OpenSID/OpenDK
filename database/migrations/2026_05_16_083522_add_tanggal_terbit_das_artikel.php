<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('das_artikel', function (Blueprint $table){
            $table->addColumn('date','tanggal_terbit')->nullable();
        });
        DB::statement("UPDATE das_artikel SET tanggal_terbit = created_at WHERE tanggal_terbit IS NULL");
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('das_artikel', function (Blueprint $table){
            $table->dropColumn('tanggal_terbit');
        });
    }
};
