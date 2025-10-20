<?php

use App\Models\SettingAplikasi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

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

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'telegram_chat_id')) {
                $table->dropColumn('telegram_chat_id');
            }
        });
    }

    public function down(): void
    {
           
    }
};
