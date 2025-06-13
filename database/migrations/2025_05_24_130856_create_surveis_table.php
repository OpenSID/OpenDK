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
        Schema::create('das_survei', function (Blueprint $table) {
            $table->id();
            $table->string('session_id')->nullable(); // Untuk melacak sesi
            $table->string('response'); // Jawaban survei (misalnya, "Sangat Baik")
            $table->boolean('consent')->default(false); // Persetujuan privasi
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_survei');
    }
};
