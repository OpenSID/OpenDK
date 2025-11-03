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
            // Add telegram_id column
            $table->string('telegram_id', 100)->nullable()->after('phone');
            
            // Remove otp_identifier column as we'll use email and telegram_id directly
            if (Schema::hasColumn('users', 'otp_identifier')) {
                $table->dropColumn('otp_identifier');
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
        Schema::table('users', function (Blueprint $table) {
            // Restore otp_identifier
            $table->string('otp_identifier', 255)->nullable()->after('otp_channel');
            
            // Remove telegram_id
            if (Schema::hasColumn('users', 'telegram_id')) {
                $table->dropColumn('telegram_id');
            }
        });
    }
};
