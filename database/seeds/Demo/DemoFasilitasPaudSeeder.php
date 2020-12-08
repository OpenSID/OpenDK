<?php

namespace Database\Seeds\Demo;

use App\Imports\ImporFasilitasPaud;
use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Request;
use Maatwebsite\Excel\Facades\Excel;

class DemoFasilitasPaudSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        Excel::import(
            new ImporFasilitasPaud(Request::merge([
                'semester' => 1,
                'tahun'    => now()->year,
                'desa_id'  => DataDesa::first()->desa_id,
            ])),
            'template_upload/Format_Upload_Failitas_PAUD.xlsx',
            'public'
        );
    }
}
