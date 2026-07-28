<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSuratIdToLogTteTable extends Migration
{
    public function up()
    {
        Schema::table('das_log_tte', function (Blueprint $table) {
            $table->unsignedBigInteger('surat_id')->nullable()->after('id');
        });
    }

    public function down()
    {
        Schema::table('das_log_tte', function (Blueprint $table) {
            $table->dropColumn('surat_id');
        });
    }
}
