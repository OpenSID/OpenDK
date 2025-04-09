<?php

use App\Models\CounterPage;
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
        Schema::table('das_counter_page', function (Blueprint $table) {
            $counter = CounterPage::where('page', 'publikasi.galeri')->first();

            if($counter){
                $counter->delete();
            }
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_counter_page', function (Blueprint $table) {
            //
        });
    }
};
