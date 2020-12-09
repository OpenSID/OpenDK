<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporImunisasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemoImunisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporImunisasi(Request::merge([
                'bulan' => now()->month,
                'tahun' => now()->year,
            ])),
            'template_upload/Format_Upload_Cakupan_Imunisasi.xlsx',
            'public'
        );
    }
}
