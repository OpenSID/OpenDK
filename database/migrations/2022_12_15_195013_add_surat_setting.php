<?php

use App\Enums\Status;
use App\Models\SettingAplikasi;
use Illuminate\Database\Migrations\Migration;

class AddSuratSetting extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        $data = [
            ['key'=>'verifikasi_camat', 'value'=> Status::TidakAktif, 'type' => 'boolean', 'description' => 'Surat diverifikasi oleh camat atau tidak', 'kategori' => 'surat', 'option' => '{}'],
            ['key'=>'verifikasi_sekretaris', 'value'=> Status::TidakAktif, 'type' => 'boolean', 'description' => 'Surat diverifikasi oleh sekretaris atau tidak', 'kategori' => 'surat', 'option' => '{}'],
        ];

        SettingAplikasi::insert($data);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        SettingAplikasi::where('key', 'verifikasi_camat')->orWhere('key', 'verifikasi_sekretaris')->delete();
    }
}
