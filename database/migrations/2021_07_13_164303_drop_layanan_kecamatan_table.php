<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropLayananKecamatanTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::dropIfExists('das_proses_ektp');
        Schema::dropIfExists('das_proses_kk');
        Schema::dropIfExists('das_proses_akta_lahir');
        Schema::dropIfExists('das_proses_domisili');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
}
