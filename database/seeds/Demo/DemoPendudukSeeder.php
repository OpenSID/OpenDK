<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporPenduduk;
use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemoPendudukSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporPenduduk(Request::merge([
                'tahun'   => now()->year,
                'desa_id' => DataDesa::first()->desa_id,
            ])),
            'template_upload/Format_Upload_Penduduk.xlsx',
            'public'
        );
    }
}
