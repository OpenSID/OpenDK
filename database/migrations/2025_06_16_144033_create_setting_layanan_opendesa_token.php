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
            SettingAplikasi::create([
                'key' => 'layanan_opendesa_token',
                'value' => 0,
                'type' => 'input',
                'description' => 'Token pelanggan Layanan OpenDESA',
                'kategori' => 'pelanggan',
                'option' => '{}',
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
