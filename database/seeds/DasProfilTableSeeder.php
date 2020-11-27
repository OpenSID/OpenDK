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
            'alamat'                          => env('PROFIL_ALAMAT'),
            'kode_pos'                        => env('PROFIL_KODE_POS'),
            'telepon'                         => env('PROFIL_TELEPON'),
            'email'                           => env('PROFIL_EMAIL'),
            'tahun_pembentukan'               => env('PROFIL_TAHUN_PEMBENTUKAN'),
            'dasar_pembentukan'               => env('PROFIL_DASAR_PEMBENTUKAN'),
            'nama_camat'                      => env('PROFIL_NAMA_CAMAT'),
            'sekretaris_camat'                => env('PROFIL_SEKRETARIS_CAMAT'),
            'kepsek_pemerintahan_umum'        => env('PROFIL_PEMERINTAH_UMUM'),
            'kepsek_kesejahteraan_masyarakat' => env('PROFIL_KESEJAHTERAAN_MASYARAKAT'),
            'kepsek_pemberdayaan_masyarakat'  => env('PROFIL_PEMBERDAYAAN_MASYARAKAT'),
            'kepsek_pelayanan_umum'           => env('PROFIL_KEPSEK_PELAYAN_UMUM'),
            'kepsek_trantib'                  => env('PROFIL_KEPSEK_TRANTIB'),
            'file_struktur_organisasi'        => env('PROFIL_FILE_STRUKTUR_ORGANISASI'),
            'file_logo'                       => env('PROFIL_FILE_LOGO'),
            'visi'                            => env('PROFIL_VISI'),
            'misi'                            => env('PROFIL_MISI'),
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
