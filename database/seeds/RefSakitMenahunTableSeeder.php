<?php

use Illuminate\Database\Seeder;

class RefSakitMenahunTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('ref_sakit_menahun')->truncate();
        
        DB::table('ref_sakit_menahun')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'JANTUNG',
            ),
            1 => array (
                'id' => 2,
                'nama' => 'LEVER',
            ),
            2 => array (
                'id' => 3,
                'nama' => 'PARU-PARU',
            ),
            3 => array (
                'id' => 4,
                'nama' => 'KANKER',
            ),
            4 => array (
                'id' => 5,
                'nama' => 'STROKE',
            ),
            5 => array (
                'id' => 6,
                'nama' => 'DIABETES MELITUS',
            ),
            6 => array (
                'id' => 7,
                'nama' => 'GINJAL',
            ),
            7 => array (
                'id' => 8,
                'nama' => 'MALARIA',
            ),
            8 => array (
                'id' => 9,
                'nama' => 'LEPRA/KUSTA',
            ),
            9 => array (
                'id' => 10,
                'nama' => 'HIV/AIDS',
            ),
            10 => array (
                'id' => 11,
                'nama' => 'GILA/STRESS',
            ),
            11 => array (
                'id' => 12,
                'nama' => 'TBC',
            ),
            12 => array (
                'id' => 13,
                'nama' => 'ASTHMA',
            ),
            13 => array (
                'id' => 14,
                'nama' => 'TIDAK ADA/TIDAK SAKIT',
            ),
        ));
    }
}