<?php

namespace Database\Seeds\Demo;

use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use App\Imports\ImporFasilitasPaud;
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
        
        DB::table('das_fasilitas_paud')->truncate();

        Excel::import(
            new ImporFasilitasPaud([
                'semester' => 1,
                'tahun'    => now()->year,
                'desa_id'  => DataDesa::first()->desa_id,
            ]),
            'template_upload/Format_Upload_Fasilitas_PAUD.xlsx',
            'public'
        );
    }
}
