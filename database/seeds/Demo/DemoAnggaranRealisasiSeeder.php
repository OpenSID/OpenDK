<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporAnggaranRealisasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemoAnggaranRealisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporAnggaranRealisasi(Request::merge([
                'bulan' => now()->month,
                'tahun' => now()->year,
            ])),
            'template_upload/Format_Upload_Anggaran_Realisasi.xlsx',
            'public'
        );
    }
}
