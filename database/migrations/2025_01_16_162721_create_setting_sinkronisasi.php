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
        // Tambahkan setting untuk sinkronisasi database gabungan
        SettingAplikasi::insert([            
            'key' => 'sinkronisasi_database_gabungan',
            'value' => Status::TidakAktif,
            'type' => 'boolean',
            'description' => 'Aktifkan Sinkronisasi ke Database Gabungan.',
            'kategori' => 'sinkronisasi',
            'option' => '{}',
        ]);
        SettingAplikasi::insert([            
            'key' => 'api_server_database_gabungan',
            'value' => '',
            'type' => 'input',
            'description' => 'Alamat Server Database Gabungan.',
            'kategori' => 'sinkronisasi',
            'option' => '{}',
        ]);
        SettingAplikasi::insert([            
            'key' => 'api_key_database_gabungan',
            'value' => '',
            'type' => 'textarea',
            'description' => 'API Key Untuk Sinkronisasi Data Dari Database Gabungan.',
            'kategori' => 'sinkronisasi',
            'option' => '{}',
        ]);

        // Tambahkan setting untuk sinkronisasi opensid
        SettingAplikasi::insert([            
            'key' => 'api_key_opendk',
            'value' => '',
            'type' => 'textarea',
            'description' => 'OpenDK API Key Untuk Sinkronisasi Data.',
            'kategori' => 'sinkronisasi',
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
        SettingAplikasi::whereIn('key', ['sinkronisasi_database_gabungan','api_server_database_gabungan', 'api_key_database_gabungan', 'api_key_opendk'])->delete();
    }
};
