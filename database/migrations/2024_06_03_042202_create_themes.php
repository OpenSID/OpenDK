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
        Schema::create('das_themes', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('vendor');
            $table->string('version');
            $table->string('description')->nullable();
            $table->string('path');
            $table->string('screenshot')->nullable();
            $table->boolean('active')->default(0);
            $table->boolean('system')->default(0);
            $table->mediumText('options')->nullable();
            $table->timestamps();
        });

        scan_themes();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_themes');
    }
};
