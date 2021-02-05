<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporToiletSanitasi;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class DemoToiletSanitasiSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporToiletSanitasi([
                'bulan' => now()->month,
                'tahun' => now()->year,
            ]),
            'template_upload/Format_Upload_Toilet_&_Sanitasi.xlsx',
            'public'
        );
    }
}
