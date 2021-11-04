<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporImunisasi;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;

class DemoImunisasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('das_imunisasi')->truncate();

        Excel::import(
            new ImporImunisasi([
                'bulan' => now()->month,
                'tahun' => now()->year,
            ]),
            'template_upload/Format_Upload_Cakupan_Imunisasi.xlsx',
            'public'
        );
    }
}
