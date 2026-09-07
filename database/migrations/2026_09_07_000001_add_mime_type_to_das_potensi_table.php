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
        Schema::table('das_potensi', function (Blueprint $table) {
            if (!Schema::hasColumn('das_potensi', 'mime_type')) {
                $table->string('mime_type', 50)->nullable()->after('file_gambar');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('das_potensi', function (Blueprint $table) {
            if (Schema::hasColumn('das_potensi', 'mime_type')) {
                $table->dropColumn('mime_type');
            }
        });
    }
};
