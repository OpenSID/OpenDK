<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\Profil;
use App\Facades\Counter;
use Illuminate\Support\Facades\DB;

use function compact;
use function config;
use function strtolower;
use function ucwords;
use function view;

class DashboardProfilController extends Controller
{
    /**
     * Menampilkan Data Profil Kecamatan
     **/

    public function showProfile()
    {
        Counter::count('dashboard.profil');

        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Profil';
        if (isset($profil)) {
            $page_description = ucwords(strtolower('Kecamatan ' . $profil->kecamatan->nama));
        }

        return view('dashboard.profil.show_profil', compact('page_title', 'page_description', 'profil', 'defaultProfil', 'dokumen'));
    }
}
