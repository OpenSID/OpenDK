<?php

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class AddSlugProsedur extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
         Schema::table('das_prosedur', function (Blueprint $table) {
            $table->Char('slug', 50)->after('judul_prosedur')->nullable(false);
        });

        // update data slug pada das prosedur
        $prosedur = DB::table('das_prosedur')->update(['slug' => DB::raw("replace(judul_prosedur, ' ' , '-')")]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('das_prosedur', function (Blueprint $table) {
            $table->dropColumn('slug');
        });
    }
}
