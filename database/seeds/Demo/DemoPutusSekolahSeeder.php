<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporPutusSekolah;
use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemoPutusSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporPutusSekolah([
                'semester' => 1,
                'tahun'    => now()->year,
                'desa_id'  => DataDesa::first()->desa_id,
            ]),
            'template_upload/Format_Upload_Putus_Sekolah.xlsx',
            'public'
        );
    }
}
