<?php

use App\Models\SettingAplikasi;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        $twoFa = SettingAplikasi::where('key', 'login_2fa')->first();
        if ($twoFa) {
            $twoFa->delete();
        }

        $setting = SettingAplikasi::where('key', 'login_otp')->first();
        if ($setting) {
            $setting->description = 'Aktifkan fitur login dengan OTP (One-Time Password) atau 2FA (Two-Factor Authentication) untuk keamanan tambahan';
            $setting->save();
        }
    }

    public function down(): void
    {
           
    }
};
