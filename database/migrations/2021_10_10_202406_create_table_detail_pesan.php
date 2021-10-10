<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class CreateTableDetailPesan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_pesan_detail', function (Blueprint $table) {
            $table->bigIncrements('id');
            $table->unsignedInteger('pesan_id');
            $table->text('text');
            $table->unsignedInteger('desa_id')->nullable();
            $table->foreign('pesan_id')
                ->references('id')
                ->on('das_pesan')
                ->onDelete('cascade')
                ->onUpdate('cascade');
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
        Schema::disableForeignKeyConstraints();
        Schema::dropIfExists('das_pesan_detail');
        Schema::enableForeignKeyConstraints();
    }
}
