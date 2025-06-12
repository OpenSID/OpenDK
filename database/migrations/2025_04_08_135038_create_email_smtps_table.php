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
        Schema::create('ref_smtp', function (Blueprint $table) {
            $table->id();
            $table->string('provider');
            $table->string('host');
            $table->string('port');
            $table->string('username');
            $table->string('password');
            $table->boolean('status')->default(1)->comment('0=tidak aktif, 1=aktif');
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
        Schema::dropIfExists('ref_smtp');
    }
};
