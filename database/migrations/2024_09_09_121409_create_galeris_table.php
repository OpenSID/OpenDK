<?php

use App\Models\Album;
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
        Schema::create('galeris', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->foreignIdFor(Album::class)->constrained()->cascadeOnDelete()->cascadeOnUpdate();
            $table->string('judul');
            $table->text('gambar')->nullable();
            $table->string('link')->nullable();
            $table->boolean('status')->default(1)->comment('0=tidak aktif, 1=aktif');
            $table->enum('jenis', ['file', 'url'])->default('file');
            $table->string('slug')->unique();
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
        Schema::dropIfExists('galeris');
    }
};
