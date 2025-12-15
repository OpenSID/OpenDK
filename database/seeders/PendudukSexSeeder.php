<?php

namespace Database\Seeders;

use App\Models\PendudukSex;
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
        // Insert data ke tabel das_penduduk_sex
        if(!PendudukSex::whereNama('Laki-laki')->exists()){
            PendudukSex::create(['nama' => 'Laki-laki']);
        }
        if(!PendudukSex::whereNama('Perempuan')->exists()){
            PendudukSex::create(['nama' => 'Perempuan']);
        }
    }
}
