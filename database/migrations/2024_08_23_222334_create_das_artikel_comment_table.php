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
        Schema::create('das_artikel_comment', function (Blueprint $table) {
            $table->id();
            $table->foreignId('das_artikel_id')->constrained('das_artikel')->onDelete('cascade'); // Menghubungkan komentar dengan artikel
            $table->string('status')->nullable();
            $table->string('nama');
            $table->string('email')->nullable();
            $table->text('body');
            $table->unsignedBigInteger('comment_id')->nullable();
            $table->foreign('comment_id')->references('id')->on('das_artikel_comment')->onDelete('cascade'); // Foreign key ke tabel komentar yang sama
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
        Schema::dropIfExists('das_artikel_comment');
    }
};
