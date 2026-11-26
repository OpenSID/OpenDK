<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/*
 * Menambahkan kolom url_hash (sha256 dari url) dan index pada tabel visitors
 * agar statistik pengunjung tidak memindai seluruh tabel (issue #1817).
 */
return new class extends Migration
{
    public function up(): void
    {
        if (! Schema::hasColumn('visitors', 'url_hash')) {
            Schema::table('visitors', function (Blueprint $table) {
                $table->char('url_hash', 64)->nullable()->after('url');
            });
        }

        // Backfill data lama. SHA2 dijalankan di sisi database agar tidak memuat 500 ribu baris ke PHP.
        DB::statement('UPDATE visitors SET url_hash = SHA2(url, 256) WHERE url_hash IS NULL');

        Schema::table('visitors', function (Blueprint $table) {
            $table->index('visited_at', 'visitors_visited_at_index');
            $table->index('url_hash', 'visitors_url_hash_index');
            $table->index(['ip_address', 'url_hash', 'visited_at'], 'visitors_ip_url_visited_index');
        });
    }

    public function down(): void
    {
        Schema::table('visitors', function (Blueprint $table) {
            $table->dropIndex('visitors_visited_at_index');
            $table->dropIndex('visitors_url_hash_index');
            $table->dropIndex('visitors_ip_url_visited_index');
        });

        Schema::table('visitors', function (Blueprint $table) {
            $table->dropColumn('url_hash');
        });
    }
};
