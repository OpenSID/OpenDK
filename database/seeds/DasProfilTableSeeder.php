<?php

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasProfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kecamatan_id = config('app.default_profile');

        DB::table('das_profil')->insert([
            'provinsi_id'                     => substr($kecamatan_id, 0, 2),
            'kabupaten_id'                    => substr($kecamatan_id, 0, 5),
            'kecamatan_id'                    => $kecamatan_id,
            'alamat'                          => null,
            'kode_pos'                        => null,
            'telepon'                         => null,
            'email'                           => null,
            'tahun_pembentukan'               => null,
            'dasar_pembentukan'               => null,
            'nama_camat'                      => null,
            'sekretaris_camat'                => null,
            'kepsek_pemerintahan_umum'        => null,
            'kepsek_kesejahteraan_masyarakat' => null,
            'kepsek_pemberdayaan_masyarakat'  => null,
            'kepsek_pelayanan_umum'           => null,
            'kepsek_trantib'                  => null,
            'file_struktur_organisasi'        => null,
            'file_logo'                       => null,
            'visi'                            => null,
            'misi'                            => null,
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
