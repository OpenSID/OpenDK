<?php

use Illuminate\Database\Seeder;

class DasKategoriKomplainTableSeeder extends Seeder
{

    /**
     * Auto generated seed file
     *
     * @return void
     */
    public function run()
    {
        

        \DB::table('das_kategori_komplain')->delete();
        
        \DB::table('das_kategori_komplain')->insert(array (
            0 => 
            array (
                'id' => '1',
                'slug' => 'infrastruktur-sanitasi-air',
            'nama' => 'Infrastruktur (Sanitasi & Air)',
                'created_at' => '2018-04-25 16:49:07',
                'updated_at' => '2018-04-25 16:49:07',
            ),
            1 => 
            array (
                'id' => '3',
                'slug' => 'pendidikan',
                'nama' => 'Pendidikan',
                'created_at' => '2018-04-25 17:04:27',
                'updated_at' => '2018-04-25 17:04:27',
            ),
            2 => 
            array (
                'id' => '4',
                'slug' => 'kesehatan',
                'nama' => 'Kesehatan',
                'created_at' => '2018-04-25 17:04:41',
                'updated_at' => '2018-04-25 18:21:05',
            ),
            3 => 
            array (
                'id' => '5',
                'slug' => 'anggaran-desa',
                'nama' => 'Anggaran Desa',
                'created_at' => '2018-04-25 17:04:51',
                'updated_at' => '2018-04-25 17:04:51',
            ),
            4 => 
            array (
                'id' => '6',
                'slug' => 'lainnya',
                'nama' => 'Lainnya',
                'created_at' => '2018-04-25 17:05:00',
                'updated_at' => '2018-04-25 17:05:00',
            ),
        ));
        
        
    }
}