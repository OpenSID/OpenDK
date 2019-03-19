<?php

use Illuminate\Database\Seeder;

class DasTipeRegulasiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('das_tipe_regulasi')->delete();
        
        \DB::table('das_tipe_regulasi')->insert(array (
            0 => 
            array (
                'id' => 2,
                'nama' => 'Regulasi Nasional',
                'slug' => 'regulasi-nasional',
                'created_at' => '2018-04-25 19:26:51',
                'updated_at' => '2018-04-25 19:26:51',
            ),
            1 => 
            array (
                'id' => 3,
                'nama' => 'Regulasi Daerah',
                'slug' => 'regulasi-daerah',
                'created_at' => '2018-04-25 19:27:00',
                'updated_at' => '2018-04-25 19:27:00',
            ),
        ));
        
        
    }
}