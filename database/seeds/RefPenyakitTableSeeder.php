<?php

use Illuminate\Database\Seeder;

class RefPenyakitTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('ref_penyakit')->truncate();
        
        DB::table('ref_penyakit')->insert(array (
            0 => array (
                'id' => 1,
                'nama' => 'Demam Berdarah',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => array (
                'id' => 2,
                'nama' => 'Kolera',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => array (
                'id' => 3,
                'nama' => 'Malaria',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => array (
                'id' => 4,
                'nama' => 'Influensa',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}