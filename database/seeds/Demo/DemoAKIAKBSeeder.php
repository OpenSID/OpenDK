<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporAKIAKB;
use Illuminate\Database\Seeder;
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
