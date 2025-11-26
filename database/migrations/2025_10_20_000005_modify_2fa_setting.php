<?php

use App\Models\SettingAplikasi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up(): void
    {
        DB::table('das_setting')->where('key', 'login_2fa')->delete();        
        DB::table('das_setting')->where('key', 'login_otp')->update(['description' => 'Aktifkan fitur login dengan OTP (One-Time Password) atau 2FA (Two-Factor Authentication) untuk keamanan tambahan']);

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
