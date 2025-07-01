<?php

use App\Models\SettingAplikasi;
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
        $exists = SettingAplikasi::where('key', 'layanan_opendesa_token')->exists();
        if (! $exists) {
            SettingAplikasi::insert([
                'key' => 'layanan_opendesa_token',
                'value' => 0,
                'type' => 'input',
                'description' => 'Token pelanggan Layanan OpenDESA',
                'kategori' => 'pelanggan',
                'option' => '{}',
            ]);
        }

        $exists = SettingAplikasi::where('key', 'jenis_peta')->exists();
        if (! $exists) {
            SettingAplikasi::insert([
                'key' => 'jenis_peta',
                'value' => '',
                'type' => 'boolean_peta',
                'description' => 'Jenis Peta yang akan diterapkan secara bawaan sistem',
                'kategori' => 'web',
                'option' => '{"readonly":true}',
            ]);
        }
        $exists = SettingAplikasi::where('key', 'map_box')->exists();
        if (! $exists) {
            SettingAplikasi::insert([
                'key' => 'map_box',
                'value' => '',
                'type' => 'input',
                'description' => 'Map Box Api Key untuk peta',
                'kategori' => 'web',
                'option' => '{"readonly":true}',
            ]);
        }
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
