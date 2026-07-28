<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddFileHashToLogSuratTable extends Migration
{
    public function up()
    {
        Schema::table('das_log_surat', function (Blueprint $table) {
            $table->string('file_hash', 64)->nullable()->after('file');
        });
    }

    public function down()
    {
        Schema::table('das_log_surat', function (Blueprint $table) {
            $table->dropColumn('file_hash');
        });
    }
}
