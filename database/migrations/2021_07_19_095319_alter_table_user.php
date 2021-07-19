<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB as DB;

class AlterTableUser extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        // Gabung kolom first_name dan last_name
        DB::table('users')
            ->update([
                "first_name" => DB::raw("CONCAT(`first_name`, ' ', `last_name`)"),
            ]);
        // Pakai DB::statement karena ada kolom enum di tabel users
        DB::statement('ALTER TABLE users CHANGE `first_name` `name` VARCHAR(191) default null');
        Schema::table('users', function(Blueprint $table)
        {
            $table->dropColumn('last_name');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        // Kembalikan kolom first_name dan last_name
        DB::statement('ALTER TABLE tableName CHANGE `name` `first_name` VARCHAR(191) default null');
        Schema::table('users', function(Blueprint $table)
        {
            $table->string('last_name')->nullable();
        });
    }
}
