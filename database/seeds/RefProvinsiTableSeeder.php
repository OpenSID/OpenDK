<?php

use Illuminate\Database\Seeder;

class RefProvinsiTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('ref_provinsi')->delete();
        
        \DB::table('ref_provinsi')->insert(array (
            0 => 
            array (
                'id' => '11',
                'nama' => 'ACEH',
            ),
            1 => 
            array (
                'id' => '12',
                'nama' => 'SUMATERA UTARA',
            ),
            2 => 
            array (
                'id' => '13',
                'nama' => 'SUMATERA BARAT',
            ),
            3 => 
            array (
                'id' => '14',
                'nama' => 'RIAU',
            ),
            4 => 
            array (
                'id' => '15',
                'nama' => 'JAMBI',
            ),
            5 => 
            array (
                'id' => '16',
                'nama' => 'SUMATERA SELATAN',
            ),
            6 => 
            array (
                'id' => '17',
                'nama' => 'BENGKULU',
            ),
            7 => 
            array (
                'id' => '18',
                'nama' => 'LAMPUNG',
            ),
            8 => 
            array (
                'id' => '19',
                'nama' => 'KEPULAUAN BANGKA BELITUNG',
            ),
            9 => 
            array (
                'id' => '21',
                'nama' => 'KEPULAUAN RIAU',
            ),
            10 => 
            array (
                'id' => '31',
                'nama' => 'DKI JAKARTA',
            ),
            11 => 
            array (
                'id' => '32',
                'nama' => 'JAWA BARAT',
            ),
            12 => 
            array (
                'id' => '33',
                'nama' => 'JAWA TENGAH',
            ),
            13 => 
            array (
                'id' => '34',
                'nama' => 'DI YOGYAKARTA',
            ),
            14 => 
            array (
                'id' => '35',
                'nama' => 'JAWA TIMUR',
            ),
            15 => 
            array (
                'id' => '36',
                'nama' => 'BANTEN',
            ),
            16 => 
            array (
                'id' => '51',
                'nama' => 'BALI',
            ),
            17 => 
            array (
                'id' => '52',
                'nama' => 'NUSA TENGGARA BARAT',
            ),
            18 => 
            array (
                'id' => '53',
                'nama' => 'NUSA TENGGARA TIMUR',
            ),
            19 => 
            array (
                'id' => '61',
                'nama' => 'KALIMANTAN BARAT',
            ),
            20 => 
            array (
                'id' => '62',
                'nama' => 'KALIMANTAN TENGAH',
            ),
            21 => 
            array (
                'id' => '63',
                'nama' => 'KALIMANTAN SELATAN',
            ),
            22 => 
            array (
                'id' => '64',
                'nama' => 'KALIMANTAN TIMUR',
            ),
            23 => 
            array (
                'id' => '65',
                'nama' => 'KALIMANTAN UTARA',
            ),
            24 => 
            array (
                'id' => '71',
                'nama' => 'SULAWESI UTARA',
            ),
            25 => 
            array (
                'id' => '72',
                'nama' => 'SULAWESI TENGAH',
            ),
            26 => 
            array (
                'id' => '73',
                'nama' => 'SULAWESI SELATAN',
            ),
            27 => 
            array (
                'id' => '74',
                'nama' => 'SULAWESI TENGGARA',
            ),
            28 => 
            array (
                'id' => '75',
                'nama' => 'GORONTALO',
            ),
            29 => 
            array (
                'id' => '76',
                'nama' => 'SULAWESI BARAT',
            ),
            30 => 
            array (
                'id' => '81',
                'nama' => 'MALUKU',
            ),
            31 => 
            array (
                'id' => '82',
                'nama' => 'MALUKU UTARA',
            ),
            32 => 
            array (
                'id' => '91',
                'nama' => 'PAPUA BARAT',
            ),
            33 => 
            array (
                'id' => '94',
                'nama' => 'PAPUA',
            ),
        ));
        
        
    }
}