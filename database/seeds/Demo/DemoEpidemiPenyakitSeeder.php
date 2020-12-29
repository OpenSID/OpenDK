<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporEpidemiPenyakit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemoEpidemiPenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporEpidemiPenyakit([
                'penyakit_id' => 1,
                'bulan'       => now()->month,
                'tahun'       => now()->year,
            ]),
            'template_upload/Format_Upload_Epidemi_Penyakit.xlsx',
            'public'
        );
    }
}
