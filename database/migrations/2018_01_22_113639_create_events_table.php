<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateEventsTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::create('das_events', function (Blueprint $table) {
            $table->increments('id');
            $table->string('event_name', 150);
            $table->dateTime('start');
            $table->dateTime('end');
            $table->string('attendants', 100);
            $table->longText('description');
            $table->string('status', 10);
            $table->string('attachment', 255)->nullable();
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
        Schema::dropIfExists('das_events');
    }
}
