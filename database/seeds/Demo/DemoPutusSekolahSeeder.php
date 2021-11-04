<?php

namespace Database\Seeds\Demo;

use App\Models\DataDesa;
use Illuminate\Database\Seeder;
use App\Imports\ImporPutusSekolah;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Request;

class DemoPutusSekolahSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        DB::table('das_putus_sekolah')->truncate();

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
