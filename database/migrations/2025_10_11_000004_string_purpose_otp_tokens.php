<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('otp_tokens', function (Blueprint $table) {
            $table->string('purpose', 50)->default('login')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('otp_tokens', function (Blueprint $table) {
            $table->string('purpose', 50)->default('login')->change();
            // $table->enum('purpose', ['activation', 'login'])->default('login');
        });
    }
};
