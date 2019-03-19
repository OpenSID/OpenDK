<?php

use Illuminate\Database\Seeder;

class RefPendidikanKkTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ref_pendidikan_kk')->delete();
        
        \DB::table('ref_pendidikan_kk')->insert(array (
            0 => 
            array (
                'id' => 1,
                'nama' => 'TIDAK / BELUM SEKOLAH',
            ),
            1 => 
            array (
                'id' => 2,
                'nama' => 'BELUM TAMAT SD/SEDERAJAT',
            ),
            2 => 
            array (
                'id' => 3,
                'nama' => 'TAMAT SD / SEDERAJAT',
            ),
            3 => 
            array (
                'id' => 4,
                'nama' => 'SLTP/SEDERAJAT',
            ),
            4 => 
            array (
                'id' => 5,
                'nama' => 'SLTA / SEDERAJAT',
            ),
            5 => 
            array (
                'id' => 6,
                'nama' => 'DIPLOMA I / II',
            ),
            6 => 
            array (
                'id' => 7,
                'nama' => 'AKADEMI/ DIPLOMA III/S. MUDA',
            ),
            7 => 
            array (
                'id' => 8,
                'nama' => 'DIPLOMA IV/ STRATA I',
            ),
            8 => 
            array (
                'id' => 9,
                'nama' => 'STRATA II',
            ),
            9 => 
            array (
                'id' => 10,
                'nama' => 'STRATA III',
            ),
        ));
        
        
    }
}