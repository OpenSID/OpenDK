<?php

use Illuminate\Database\Seeder;

class RefCacatTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('ref_cacat')->truncate();
        
        DB::table('ref_cacat')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'CACAT FISIK',
            ),
            1 => array (
                'id' => 2,
                'nama' => 'CACAT NETRA/BUTA',
            ),
            2 => array (
                'id' => 3,
                'nama' => 'CACAT RUNGU/WICARA',
            ),
            3 => array (
                'id' => 4,
                'nama' => 'CACAT MENTAL/JIWA',
            ),
            4 => array (
                'id' => 5,
                'nama' => 'CACAT FISIK DAN MENTAL',
            ),
            5 => array (
                'id' => 6,
                'nama' => 'CACAT LAINNYA',
            ),
            6 => array (
                'id' => 7,
                'nama' => 'TIDAK CACAT',
            ),
        ));
    }
}