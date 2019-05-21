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
                'desa_id' => '1308020001',
                'kecamatan_id' => '1308020',
                'nama' => 'MUNGO',
                'website' => NULL,
                'luas_wilayah' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            1 =>
            array (
                'id' => 2,
                'desa_id' => '1308020002',
                'kecamatan_id' => '1308020',
                'nama' => 'ANDALEH',
                'website' => NULL,
                'luas_wilayah' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            2 =>
            array (
                'id' => 3,
                'desa_id' => '1308020003',
                'kecamatan_id' => '1308020',
                'nama' => 'SUNGAI KAMUYANG',
                'website' => NULL,
                'luas_wilayah' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
            3 =>
            array (
                'id' => 4,
                'desa_id' => '1308020004',
                'kecamatan_id' => '1308020',
                'nama' => 'TANJUANG ARO SIKABU-KABU PD. PANJANG',
                'website' => NULL,
                'luas_wilayah' => NULL,
                'created_at' => NULL,
                'updated_at' => NULL,
            ),
        ));


    }
}