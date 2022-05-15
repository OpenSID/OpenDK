<?php

use App\Models\DataDesa;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DefaultSebutanDesa extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Isi sebutan desa jika kosong
        DataDesa::whereNull('sebutan_desa')->update(['sebutan_desa' => 'desa']);

        Schema::table('das_data_desa', function (Blueprint $table) {
            $table->string('sebutan_desa')->default('desa')->change();
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_data_desa', function (Blueprint $table) {
            $table->string('sebutan_desa')->default(null)->change();
        });
    }
}
