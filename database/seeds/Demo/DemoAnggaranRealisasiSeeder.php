<?php

namespace Database\Seeds\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImporAnggaranRealisasi;

class DemoAnggaranRealisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('das_anggaran_realisasi')->truncate();

        Excel::import(
            new ImporAnggaranRealisasi([
                'bulan' => now()->month,
                'tahun' => now()->year,
            ]),
            'template_upload/Format_Upload_Anggaran_Realisasi.xlsx',
            'public'
        );
    }
}
