<?php

namespace Database\Seeds\Demo;

use App\Models\JenisPenyakit;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImporEpidemiPenyakit;
use Illuminate\Support\Facades\Request;

class DemoEpidemiPenyakitSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('das_epidemi_penyakit')->truncate();
        
        Excel::import(
            new ImporEpidemiPenyakit([
                'penyakit_id' => JenisPenyakit::first()->id,
                'bulan'       => now()->month,
                'tahun'       => now()->year,
            ]),
            'template_upload/Format_Upload_Epidemi_Penyakit.xlsx',
            'public'
        );
    }
}
