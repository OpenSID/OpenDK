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
            'key' => 'google_recaptcha',
            'value' => Status::TidakAktif,
            'type' => 'boolean',
            'description' => 'Gunakan Aktif untuk Google reCAPTCHA atau Tidak Aktif untuk reCAPTCHA bawaan sistem',
            'kategori' => 'sistem',
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
        SettingAplikasi::whereIn('key', ['google_recaptcha'])->delete();
    }
};
