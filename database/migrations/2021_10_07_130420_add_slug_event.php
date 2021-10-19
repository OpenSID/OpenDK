<?php

use Illuminate\Support\Str;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugEvent extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('das_events', function (Blueprint $table) {
            $table->string('slug')->unique()->after('event_name')->nullable();
        });

        // Tambahkan slug pada data yg sudah ada
        if ($event = DB::table('das_events')->get()) {
            foreach ($event as $value) {
                DB::table('das_events')->where('id', $value->id)->update(['slug' => Str::slug($value->event_name)]);
            }
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_events', function (Blueprint $table) {
            Schema::dropIfExists('slug');
        });
    }
}
