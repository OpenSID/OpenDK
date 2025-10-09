<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        DB::table('das_setting')->insert([
            'key' => 'login_otp',
            'value' => '1',
            'type' => 'boolean',
            'kategori' => 'auth',
            'description' => 'Aktifkan fitur login dengan OTP (One-Time Password) untuk keamanan tambahan',
            'option' => json_encode([]),
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        DB::table('das_setting')->where('key', 'login_otp')->delete();
    }
};
