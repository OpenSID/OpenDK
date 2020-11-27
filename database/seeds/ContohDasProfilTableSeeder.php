<?php

use Illuminate\Database\Seeder;

class DasProfilTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $kecamatan_id = Config::get('app.default_profile');

        \DB::table('das_profil')->delete();

        \DB::table('das_profil')->insert(array (
            0 =>
            array (
                'id' => 1,
                'provinsi_id' => substr($kecamatan_id, 0, 2),
                'kabupaten_id' => substr($kecamatan_id, 0, 5),
                'kecamatan_id' => $kecamatan_id,
                'alamat' => 'Jl. Koperasi No. 1, Kab Lombok Barat, Provinsi Nusa Tenggara Barat',
                'kode_pos' => '83653',
                'telepon' => '021-2345234',
                'email' => 'admin@mail.com',
                'tahun_pembentukan' => 1990,
                'dasar_pembentukan' => 'PEREGUB No 4 1990',
                'nama_camat' => 'H. Hadi Fathurrahman, S.Sos, M.AP',
                'sekretaris_camat' => 'Drs. Zaenal Abidin',
                'kepsek_pemerintahan_umum' => 'Musyayad, S.Sos',
                'kepsek_kesejahteraan_masyarakat' => 'Suhartono, S.Sos',
                'kepsek_pemberdayaan_masyarakat' => 'Asrarudin, SE',
                'kepsek_pelayanan_umum' => 'Masturi, ST',
                'kepsek_trantib' => 'Mastur Idris, SH',
                'file_struktur_organisasi' => 'Lighthouse.jpg',
                'file_logo' => NULL,
                'visi' => NULL,
                'misi' => NULL,
                'created_at' => '2018-02-03 06:57:26',
                'updated_at' => '2018-07-19 01:29:57',
            ),
        ));
    }
}
