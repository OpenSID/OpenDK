<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use App\Models\SettingAplikasi;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
          // Tambahkan setting untuk jenis_petas
          SettingAplikasi::insert([            
            'key' => 'jenis_peta',
            'value' => '',
            'type' => 'boolean_peta',
            'description' => 'Jenis Peta yang akan diterapkan secara bawaan sistem',
            'kategori' => 'web',
            'option' => '{"readonly":true}',
        ]);   
         // Tambahkan setting untuk sinkronisasi opensid
         SettingAplikasi::insert([            
            'key' => 'map_box',
            'value' => '',
            'type' => 'input',
            'description' => 'Map Box Api Key untuk peta',
            'kategori' => 'web',
            'option' => '{"readonly":true}',
        ]);   
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
    }
};
