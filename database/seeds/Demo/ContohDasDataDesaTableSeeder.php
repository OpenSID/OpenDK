<?php

namespace Database\Seeds\Demo;

use App\Models\Profil;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class ContohDasDataDesaTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {

        $profil = Profil::first();

        DB::table('das_data_desa')->truncate();

        DB::table('das_data_desa')->insert(array (
            0 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2001',
                'nama' => 'Bedalewun',
            ),
            1 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2002',
                'nama' => 'Lebanuba',
            ),
            2 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2003',
                'nama' => 'Rianwale',
            ),
            3 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2004',
                'nama' => 'Bungalawan',
            ),
            4 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2005',
                'nama' => 'Lamawolo',
            ),
            5 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2006',
                'nama' => 'Helanlangowuyo',
            ),
            6 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2007',
                'nama' => 'Lewopao',
            ),
            7 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2008',
                'nama' => 'Nelereren',
            ),
            8 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2009',
                'nama' => 'Boleng',
            ),
            9 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2010',
                'nama' => 'Neleblolong',
            ),
            10 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2011',
                'nama' => 'Duablolong',
            ),
            11 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2012',
                'nama' => 'Lewokeleng',
            ),
            12 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2013',
                'nama' => 'Nelelamawangi',
            ),
            13 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2014',
                'nama' => 'Harubala',
            ),
            14 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2015',
                'nama' => 'Nelelamadike',
            ),
            15 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2016',
                'nama' => 'Lamabayung',
            ),
            16 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2017',
                'nama' => 'Lewat',
            ),
            17 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2018',
                'nama' => 'Dokeng',
            ),
            18 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2019',
                'nama' => 'Bayuntaa',
            ),
            19 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2020',
                'nama' => 'Nobo',
            ),
            20 => array (
                'profil_id' => $profil->id,
                'desa_id' => $profil->kecamatan_id . '.2021',
                'nama' => 'Nelelamawangi Dua',
            ),
        ));
    }
}
