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
        

        DB::table('das_kategori_komplain')->delete();
        
        DB::table('das_kategori_komplain')->insert(array (
            0 => array (
                'id' => '1',
                'slug' => 'infrastruktur-sanitasi-air',
                'nama' => 'Infrastruktur (Sanitasi & Air)',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            1 => array (
                'id' => '3',
                'slug' => 'pendidikan',
                'nama' => 'Pendidikan',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            2 => array (
                'id' => '4',
                'slug' => 'kesehatan',
                'nama' => 'Kesehatan',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            3 => array (
                'id' => '5',
                'slug' => 'anggaran-desa',
                'nama' => 'Anggaran Desa',
                'created_at' => now(),
                'updated_at' => now(),
            ),
            4 => array (
                'id' => '6',
                'slug' => 'lainnya',
                'nama' => 'Lainnya',
                'created_at' => now(),
                'updated_at' => now(),
            ),
        ));
    }
}