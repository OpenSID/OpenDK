<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('cache_keys', function (Blueprint $table) {
            $table->id();
            $table->string('key')->unique(); // Nama cache key
            $table->string('prefix'); // Prefix dari cache key
            $table->string('group')->nullable(); // Grup cache untuk pengorganisasian
            $table->timestamp('created_at')->useCurrent();
            
            // Index untuk performansi pencarian
            $table->index(['prefix']);
            $table->index(['group']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('cache_keys');
    }
};