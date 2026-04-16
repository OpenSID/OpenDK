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
        Schema::create('das_ppid_pengaturan', function (Blueprint $table) {
            $table->id();
            $table->string('kunci', 255)->unique();
            $table->text('nilai');
            $table->string('keterangan', 255)->nullable();
            $table->timestamps();
            $table->softDeletes('deleted_at', 0);
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('das_ppid_pengaturan');
    }
};
