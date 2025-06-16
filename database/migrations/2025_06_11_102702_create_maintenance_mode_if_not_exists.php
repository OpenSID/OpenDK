<?php

use App\Enums\Status;
use App\Models\SettingAplikasi;
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

        if(SettingAplikasi::where('key', 'mode_maintenance')->exists()) {
            return; // Skip if the setting already exists
        }
        SettingAplikasi::insert([            
            'key' => 'mode_maintenance',
            'value' => Status::TidakAktif,
            'type' => 'boolean',
            'description' => 'Mode maintenance.',
            'kategori' => 'web',
            'option' => '{}',
        ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        
    }
};
