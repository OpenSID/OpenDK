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
        Schema::create('nav_menus', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('url')->comment('bisa berisi link atau url');
            $table->enum('target', ['_self', '_blank', '_top'])->default('_self');
            $table->integer('parent_id')->nullable();
            $table->integer('order')->default(0);
            $table->boolean('is_show')->default(true)->comment('Status ditampilkan');
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
        Schema::dropIfExists('nav_menus');
    }
};
