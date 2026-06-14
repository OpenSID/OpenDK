<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;

class PendudukSexSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        // Truncate sebelum insert agar idempotent (aman jika dijalankan berulang)
        // FK checks sudah dinonaktifkan oleh DatabaseSeeder
        DB::table('das_penduduk_sex')->truncate();

        DB::table('das_penduduk_sex')->insert([
            ['id' => 1, 'nama' => 'Laki-laki'],
            ['id' => 2, 'nama' => 'Perempuan'],
        ]);
    }
}

