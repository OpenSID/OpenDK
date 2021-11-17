<?php

namespace Database\Seeds\Demo;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\ImporToiletSanitasi;
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
        
        DB::table('das_toilet_sanitasi')->truncate();

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
