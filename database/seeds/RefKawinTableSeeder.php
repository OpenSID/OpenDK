<?php

use Illuminate\Database\Seeder;

class RefKawinTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ref_kawin')->delete();
        
        \DB::table('ref_kawin')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama' => 'BELUM KAWIN',
            ),
            1 => 
            array (
                'id' => 2,
                'nama' => 'KAWIN',
            ),
            2 => 
            array (
                'id' => 3,
                'nama' => 'CERAI HIDUP',
            ),
            3 => 
            array (
                'id' => 4,
                'nama' => 'CERAI MATI',
            ),
        ));
        
        
    }
}