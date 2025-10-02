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
        Schema::create('das_data_sarana', function (Blueprint $table) {
            $table->bigIncrements('id');
            
            $table->unsignedInteger('desa_id');
            $table->foreign('desa_id')->references('id')->on('das_data_desa')->onDelete('cascade');

            $table->string('kategori', 191);
            $table->string('nama', 191)->nullable();
            $table->integer('jumlah')->default(0);
            $table->text('keterangan')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_data_sarana');
    }
};
