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
        $socialmedia = array(
            0 => array (
                "icon" => "fa fa-facebook",
                "link"=> null
            ), 
            1 => array (
                "icon"=> "fa fa-twitter",
                "link"=> null
            ), 
            2 => array (
                "icon"=> "fa fa-instagram",
                "link"=> null
            ), 
            3 => array (
                "icon"=> "fa fa-youtube",
                "link"=> null
            ),
        );

        DB::table('das_profil')->truncate();

        DB::table('das_profil')->insert([
            'id'                              => 1,
            'provinsi_id'                     => null,
            'nama_provinsi'                   => null,
            'kabupaten_id'                    => null,
            'nama_kabupaten'                  => null,
            'kecamatan_id'                    => null,
            'nama_kecamatan'                  => null,
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
            'socialmedia'                     => json_encode($socialmedia),
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
