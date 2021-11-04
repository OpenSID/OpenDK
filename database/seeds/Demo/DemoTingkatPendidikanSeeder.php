<?php

namespace Database\Seeds\Demo;

use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use App\Imports\ImporTingkatPendidikan;
use Illuminate\Support\Facades\Request;

class DemoTingkatPendidikanSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('das_tingkat_pendidikan')->truncate();

        Excel::import(
            new ImporTingkatPendidikan([
                'semester' => 1,
                'tahun'    => now()->year,
                'desa_id'  => DataDesa::first()->desa_id,
            ]),
            'template_upload/Format_Upload_Tingkat_Pendidikan.xlsx',
            'public'
        );
    }
}
