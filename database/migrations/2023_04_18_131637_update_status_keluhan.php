<?php

use App\Models\Komplain;
use Illuminate\Database\Migrations\Migration;

class UpdateStatusKeluhan extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Komplain::whereStatus('BELUM')->update(['status' => 'PROSES']);            
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
