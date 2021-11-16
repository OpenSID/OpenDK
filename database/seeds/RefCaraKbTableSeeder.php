<?php

use Illuminate\Database\Seeder;

class RefCaraKbTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('ref_cara_kb')->truncate();
        
        DB::table('ref_cara_kb')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'Pil',
                'sex' => 2,
            ),
            1 => array (
                'id' => 2,
                'nama' => 'IUD',
                'sex' => 2,
            ),
            2 => array (
                'id' => 3,
                'nama' => 'Suntik',
                'sex' => 2,
            ),
            3 => array (
                'id' => 4,
                'nama' => 'Kondom',
                'sex' => 1,
            ),
            4 => array (
                'id' => 5,
                'nama' => 'Susuk KB',
                'sex' => 2,
            ),
            5 => array (
                'id' => 6,
                'nama' => 'Sterilisasi Wanita',
                'sex' => 2,
            ),
            6 => array (
                'id' => 7,
                'nama' => 'Sterilisasi Pria',
                'sex' => 1,
            ),
            7 => array (
                'id' => 99,
                'nama' => 'Lainnya',
                'sex' => 3,
            ),
        ));
    }
}