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
        Schema::create('widgets', function (Blueprint $table) {
            $table->id();
            $table->mediumText('isi')->nullable();
            $table->integer('enabled')->nullable();
            $table->string('judul')->nullable();
            $table->tinyInteger('jenis_widget')->default(3)->comment("1=Sistem, 2=Statis, 3=Dinamis");
            $table->integer('urut')->nullable();
            $table->string('form_admin')->nullable();
            $table->mediumText('setting')->nullable();
            $table->string('foto')->nullable();
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
        Schema::dropIfExists('widgets');
    }
};
