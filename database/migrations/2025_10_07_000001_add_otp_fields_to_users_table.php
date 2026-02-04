<?php

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
        Schema::table('users', function (Blueprint $table) {
            $table->boolean('otp_enabled')->default(false)->after('status');
            $table->enum('otp_channel', ['email', 'telegram', 'both'])->nullable()->after('otp_enabled');
            $table->string('otp_identifier', 255)->nullable()->after('otp_channel');
            $table->string('telegram_chat_id', 100)->nullable()->after('otp_identifier');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['otp_enabled', 'otp_channel', 'otp_identifier', 'telegram_chat_id']);
        });
    }
};
