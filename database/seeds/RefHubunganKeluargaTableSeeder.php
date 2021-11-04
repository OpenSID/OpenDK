<?php

use Illuminate\Database\Seeder;

class RefHubunganKeluargaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('ref_hubungan_keluarga')->truncate();
        
        DB::table('ref_hubungan_keluarga')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'KEPALA KELUARGA',
            ),
            1 => array (
                'id' => 2,
                'nama' => 'SUAMI',
            ),
            2 => array (
                'id' => 3,
                'nama' => 'ISTRI',
            ),
            3 => array (
                'id' => 4,
                'nama' => 'ANAK',
            ),
            4 => array (
                'id' => 5,
                'nama' => 'MENANTU',
            ),
            5 => array (
                'id' => 6,
                'nama' => 'CUCU',
            ),
            6 => array (
                'id' => 7,
                'nama' => 'ORANGTUA',
            ),
            7 => array (
                'id' => 8,
                'nama' => 'MERTUA',
            ),
            8 => array (
                'id' => 9,
                'nama' => 'FAMILI LAIN',
            ),
            9 => array (
                'id' => 10,
                'nama' => 'PEMBANTU',
            ),
            10 => array (
                'id' => 11,
                'nama' => 'LAINNYA',
            ),
        ));
    }
}