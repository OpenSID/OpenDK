<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('visitors', function(Blueprint $table){
            // change column user_agent to varchar 250
            $table->string('user_agent', length: 250)->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // tidak perlu dikembalikan
    }
};
