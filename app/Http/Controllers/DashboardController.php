<?php

namespace App\Http\Controllers;

use App\Models\Profil;
use Illuminate\Support\Facades\DB;

class DashboardController extends Controller
{
    public function index()
    {
        $profil = Profil::where('kecamatan_id', config('app.default_profile'))->first();

        $page_title = 'Dashboard';
        $page_description =   ucwords(strtolower($this->sebutan_wilayah).' ' . $profil->kecamatan->nama);
        $data = [
            'desa' => $profil->datadesa->count(),
            'penduduk' => DB::table('das_penduduk')->where('status_dasar', 1)->count(),
            'keluarga' => DB::table('das_keluarga')->count(),
            'program_bantuan' => DB::table('das_program')->count(),
        ];

        return view('dashboard.index', compact('page_title', 'page_description', 'data'));
    }
}
