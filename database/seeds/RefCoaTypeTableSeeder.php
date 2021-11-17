<?php

use Illuminate\Database\Seeder;

class RefCoaTypeTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {

        DB::table('ref_coa_type')->truncate();
        
        DB::table('ref_coa_type')->insert(array (
            0 => array (
                'id' => 4,
                'type_name' => 'PENDAPATAN',
            ),
            1 => array (
                'id' => 5,
                'type_name' => 'BELANJA',
            ),
            2 => array (
                'id' => 6,
                'type_name' => 'PEMBIAYAAN',
            ),
        ));
    }
}