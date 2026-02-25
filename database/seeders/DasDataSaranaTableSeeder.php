<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;

class DasDataSaranaTableSeeder extends Seeder
{
    /**
     * Jalankan seeder untuk tabel das_data_sarana
     *
     * @return void
     */
    public function run()
    {
        // Hapus data desa lama (tanpa truncate karena ada foreign key)
        DB::table('das_data_desa')->delete();
        DB::statement('ALTER TABLE das_data_desa AUTO_INCREMENT = 1'); // reset id

        DB::table('das_data_desa')->insert([
            'id' => 1,
            'profil_id' => 1,
            'desa_id' => 1333222,
            'nama' => 'Desa Contoh',
            'sebutan_desa' => 'Desa',
            'website' => 'https://desa-contoh.id',
            'path' => json_encode(['desa-contoh']), // ✅ JSON valid
            'luas_wilayah' => 1234,
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        // Untuk das_data_sarana bisa truncate karena hanya referensi ke desa_id
        DB::table('das_data_sarana')->truncate();

        DB::table('das_data_sarana')->insert([
            [
                'id' => 2,
                'desa_id' => 1,
                'kategori' => 'puskesmas',
                'nama' => 'Puskesmas',
                'jumlah' => 1,
                'keterangan' => 'Puskesmas induk di pusat desa',
                'created_at' => now(),
                'updated_at' => now(),
            ],
            [
                'id' => 3,
                'desa_id' => 1,
                'kategori' => 'masjid_besar',
                'nama' => 'Masjid',
                'jumlah' => 7,
                'keterangan' => null,
                'created_at' => now(),
                'updated_at' => now(),
            ],
        ]);
    }
}
