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
        Schema::table('das_artikel_comment', function (Blueprint $table) {
            $table->string('ip_address')->nullable()->after('comment_id');
            $table->string('device')->nullable()->after('ip_address');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_artikel_comment', function (Blueprint $table) {
            $table->dropColumn('ip_address');
            $table->dropColumn('device');
        });
    }
};
