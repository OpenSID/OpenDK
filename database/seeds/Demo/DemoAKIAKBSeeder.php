<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporAKIAKB;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class DemoAKIAKBSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('das_akib')->truncate();

        Excel::import(
            new ImporAKIAKB([
                'bulan'   => now()->month,
                'tahun'   => now()->year,
            ]),
            'template_upload/Format_Upload_AKI_&_AKB.xlsx',
            'public'
        );
    }
}
