<?php

namespace Database\Seeds\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContohDasProfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        DB::table('das_profil')->truncate();

        DB::table('das_profil')->insert([
            'id'                              => 1,
            'provinsi_id'                     => '53',
            'nama_provinsi'                   => 'Nusa Tenggara Timur',
            'kabupaten_id'                    => '53.06',
            'nama_kabupaten'                  => 'FLORES TIMUR',
            'kecamatan_id'                    => '53.06.13',
            'nama_kecamatan'                  => 'Ile Boleng',
            'alamat'                          => 'Jl. Koperasi No. 1, Kab Lombok Barat, Provinsi Nusa Tenggara Barat',
            'kode_pos'                        => '83653',
            'telepon'                         => '0212345234',
            'email'                           => 'admin@mail.com',
            'tahun_pembentukan'               => now()->year,
            'dasar_pembentukan'               => 'PEREGUB No 4 1990',
            'nama_camat'                      => 'H. Hadi Fathurrahman, S.Sos, M.AP',
            'sekretaris_camat'                => 'Drs. Zaenal Abidin',
            'kepsek_pemerintahan_umum'        => 'Musyayad, S.Sos',
            'kepsek_kesejahteraan_masyarakat' => 'Suhartono, S.Sos',
            'kepsek_pemberdayaan_masyarakat'  => 'Asrarudin, SE',
            'kepsek_pelayanan_umum'           => 'Masturi, ST',
            'kepsek_trantib'                  => 'Mastur Idris, SH',
            'file_struktur_organisasi'        => 'Lighthouse.jpg',
            'file_logo'                       => NULL,
            'visi'                            => NULL,
            'misi'                            => NULL,
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
