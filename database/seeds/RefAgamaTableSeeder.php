<?php

use Illuminate\Database\Seeder;

class RefAgamaTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ref_agama')->delete();
        
        \DB::table('ref_agama')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama' => 'ISLAM',
            ),
            1 => 
            array (
                'id' => 2,
                'nama' => 'KRISTEN',
            ),
            2 => 
            array (
                'id' => 3,
                'nama' => 'KATHOLIK',
            ),
            3 => 
            array (
                'id' => 4,
                'nama' => 'HINDU',
            ),
            4 => 
            array (
                'id' => 5,
                'nama' => 'BUDHA',
            ),
            5 => 
            array (
                'id' => 6,
                'nama' => 'KHONGHUCU',
            ),
            6 => 
            array (
                'id' => 7,
                'nama' => 'Lainnya',
            ),
        ));
        
        
    }
}