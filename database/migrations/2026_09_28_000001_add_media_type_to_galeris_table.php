<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * Menambahkan kolom penanda tipe media (foto/video/youtube/unknown) untuk
     * galeri berbasis link, karena link Google Drive tidak memiliki ekstensi
     * sehingga jenis media tidak bisa ditentukan hanya dari URL.
     */
    public function up(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            if (! Schema::hasColumn('galeris', 'media_type')) {
                $table->string('media_type', 20)->nullable()->after('jenis')->comment('image|video|youtube|unknown untuk media berbasis link');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('galeris', function (Blueprint $table) {
            if (Schema::hasColumn('galeris', 'media_type')) {
                $table->dropColumn('media_type');
            }
        });
    }
};
