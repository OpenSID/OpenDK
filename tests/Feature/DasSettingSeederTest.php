<?php

namespace Tests\Feature;

use App\Models\SettingAplikasi;
use Tests\TestCase;

class DasSettingSeederTest extends TestCase
{
    /** @test */
    public function semua_setting_key_terdaftar_dalam_database()
    {
        $expectedKeys = [
            'judul_aplikasi',
            'artikel_kecamatan_perhalaman',
            'artikel_desa_perhalaman',
            'jumlah_artikel_desa',
            'tte',
            'tte_api',
            'tte_username',
            'tte_password',
            'pemeriksaan_camat',
            'pemeriksaan_sekretaris',
            'sinkronisasi_database_gabungan',
            'api_server_database_gabungan',
            'api_key_database_gabungan',
            'api_key_opendk',
            'layanan_opendesa_token',
            'login_2fa',
            'google_recaptcha',
            'jenis_peta',
            'map_box',
        ];

        // cek apakah key ada yang tidak ada di database
        foreach ($expectedKeys as $key) {
            $this->assertDatabaseHas('das_setting', ['key' => $key]);
        }
    }
}
