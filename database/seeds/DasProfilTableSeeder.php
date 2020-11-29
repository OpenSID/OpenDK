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
            'alamat'                          => 'Jl. Koperasi No. 1, Kab Lombok Barat, Provinsi Nusa Tenggara Barat',
            'kode_pos'                        => '83653',
            'telepon'                         => '021-2345234',
            'email'                           => 'admin@mail.com',
            'tahun_pembentukan'               => 1990,
            'dasar_pembentukan'               => 'PEREGUB No 4 1990',
            'nama_camat'                      => 'H. Hadi Fathurrahman, S.Sos, M.AP',
            'sekretaris_camat'                => 'Drs. Zaenal Abidin',
            'kepsek_pemerintahan_umum'        => 'Musyayad, S.Sos',
            'kepsek_kesejahteraan_masyarakat' => 'Suhartono, S.Sos',
            'kepsek_pemberdayaan_masyarakat'  => 'Asrarudin, SE',
            'kepsek_pelayanan_umum'           => 'Masturi, ST',
            'kepsek_trantib'                  => 'Mastur Idris, SH',
            'file_struktur_organisasi'        => 'Lighthouse.jpg',
            'file_logo'                       => null,
            'visi'                            => null,
            'misi'                            => null,
            'created_at'                      => now(),
            'updated_at'                      => now(),
        ]);
    }
}
