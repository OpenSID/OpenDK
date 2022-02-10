<?php

use App\Models\SettingAplikasi;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\Artisan;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class TambahkanDataSettingAplikasi extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $collection = SettingAplikasi::get();

        Artisan::call('db:seed', [
            '--class' => 'DasSettingTableSeeder',
        ]);

        foreach ($collection as $value) {
            $insert = [
                'key'         => $value['key'],
                'value'         => $value['value'],
                'type'          => $value['type'],
                'description'   => $value['type'],
                'kategori'      => $value['kategori'],
                'option'        => $value['option'],
            ];

            SettingAplikasi::updateOrInsert([
                'key'              => $insert['key'],
            ], $insert);
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SettingAplikasi::where('key', '!=', 'judul_aplikasi')->delete(); 
    }
}
