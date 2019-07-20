<?php

use Illuminate\Database\Seeder;

class DasDataDesaLuakTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {


        \DB::table('das_data_desa')->delete();

        \DB::table('das_data_desa')->insert(array (
            0 =>
                array (
                    'id' => 1,
                    'desa_id' => '13.07.04.2002',
                    'kecamatan_id' => '13.07.04',
                    'nama' => 'MUNGO',
                    'website' => NULL,
                    'luas_wilayah' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            1 =>
                array (
                    'id' => 2,
                    'desa_id' => '13.07.04.2004',
                    'kecamatan_id' => '13.07.04',
                    'nama' => 'ANDALEH',
                    'website' => NULL,
                    'luas_wilayah' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            2 =>
                array (
                    'id' => 3,
                    'desa_id' => '13.07.04.2003',
                    'kecamatan_id' => '13.07.04',
                    'nama' => 'SUNGAI KAMUYANG',
                    'website' => NULL,
                    'luas_wilayah' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
            3 =>
                array (
                    'id' => 4,
                    'desa_id' => '13.07.04.2001',
                    'kecamatan_id' => '13.07.04',
                    'nama' => 'TANJUANG ARO SIKABU-KABU PD. PANJANG',
                    'website' => NULL,
                    'luas_wilayah' => NULL,
                    'created_at' => NULL,
                    'updated_at' => NULL,
                ),
        ));


    }
}