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
        Schema::table('das_form_dokumen', function (Blueprint $table) {
            $table->text('description')->nullable()->after('nama_dokumen');
            $table->integer('jenis_dokumen_id')->nullable()->after('file_dokumen'); //diambil dari id list jenis dokumen yang terpilih, nullable agar tidak merusak data sebelumnya
            $table->string('jenis_dokumen')->nullable()->after('jenis_dokumen_id'); //diambil dari nama list jenis dokumen yang terpilih, nullable agar tidak merusak data sebelumnya
            $table->boolean('is_published')->default(true)->after('jenis_dokumen');// default di set true, agar data sebelumnya berstatus publish
            $table->dateTime('published_at')->nullable()->after('is_published');
            $table->integer('retention_days')->nullable()->default(0)->after('published_at'); // 0 = aktif selamanya
            $table->dateTime('expired_at')->nullable()->after('retention_days'); // null = jika retention 0 maka akan null dan aktif selamanya
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_form_dokumen', function (Blueprint $table) {
            $table->dropColumn(['description','jenis_dokumen_id', 'jenis_dokumen', 'is_published', 'published_at', 'retention_days', 'expired_at']);
        });
    }
};
