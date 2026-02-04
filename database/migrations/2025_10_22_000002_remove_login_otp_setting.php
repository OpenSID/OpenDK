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
        // Remove login_otp setting as it's no longer needed
        // OTP and 2FA will be controlled by individual user settings (otp_enabled and two_fa_enabled)
        DB::table('das_setting')->where('key', 'login_otp')->delete();
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Restore login_otp setting if needed
        DB::table('das_setting')->insert([
            'key' => 'login_otp',
            'value' => '1',
            'type' => 'boolean',
            'kategori' => 'auth',
            'description' => 'Aktifkan fitur login dengan OTP (One-Time Password) atau 2FA (Two-Factor Authentication) untuk keamanan tambahan',
            'option' => json_encode([]),
        ]);
    }
};
