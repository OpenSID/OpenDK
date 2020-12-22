<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporAPBDesa;
use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Maatwebsite\Excel\Facades\Excel;

class DemoAPBDesaSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporAPBDesa([
                'bulan' => now()->month,
                'tahun' => now()->year,
                'desa'  => DataDesa::first()->desa_id,
            ]),
            'template_upload/Format_Upload_APBDes.xlsx',
            'public'
        );
    }
}
