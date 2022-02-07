<?php

use Illuminate\Database\Seeder;

class DasSettingTableSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        DB::table('das_setting')->truncate();

        DB::table('das_setting')->insert([
            0 => [
                'id' => 1,
                'key' => 'judul_aplikasi',
                'value' => 'Kecamatan',
                'type' => 'input',
                'description' => 'Judul halaman aplikasi.',
                'kategori' => '-',
                'option' => '{}',
            ],
            1 => [
                'id' => 2,
                'key' => 'jumlah_artikel_kecamatan',
                'value' => '10',
                'type' => 'number',
                'description' => 'pengaturan limit jumlah artikel kecamatan dalam satu halaman',
                'kategori' => '-',
                'option' => '{}',
            ],
            2 => [
                'id' => 3,
                'key' => 'jumlah_artikel_desa',
                'value' => '10',
                'type' => 'number',
                'description' => 'pengaturan limit jumlah artikel desa dalam satu halaman',
                'kategori' => '-',
                'option' => '{}',
            ],
        ]);
    }
}
