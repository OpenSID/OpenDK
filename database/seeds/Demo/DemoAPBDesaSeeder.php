<?php

namespace Database\Seeds\Demo;

use App\Models\DataDesa;
use App\Imports\ImporAPBDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
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
        
        DB::table('das_anggaran_desa')->truncate();

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
