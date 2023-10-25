<?php

use App\Enums\Status;
use App\Models\SettingAplikasi;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class MaintenanceMode extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        SettingAplikasi::insert([
            'id' => 11,
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
        SettingAplikasi::where('key', 'mode_maintenance')->delete();
    }
}
