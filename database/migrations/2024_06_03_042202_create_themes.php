<?php

use App\Enums\Status;
use App\Enums\Tema;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // cek apakah tabel das_themes ada
        if (Schema::hasTable('das_themes')) {
            // Kosongkan tabel
            DB::table('das_themes')->truncate();
        } else {
            Schema::create('das_themes', function (Blueprint $table) {
                $table->id();
                $table->string('name');
                $table->string('vendor');
                $table->string('version');
                $table->string('description')->nullable();
                $table->string('path');
                $table->string('screenshot')->nullable();
                $table->boolean('active')->default(Status::TidakAktif);
                $table->boolean('system')->default(Tema::TemaKostum);
                $table->mediumText('options')->nullable();
                $table->timestamps();
            });
        }

        try {
            scan_themes();
        } catch (\Exception $e) {
            Log::error('Error scanning themes: ' . $e->getMessage());
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::dropIfExists('das_themes');
    }
};
