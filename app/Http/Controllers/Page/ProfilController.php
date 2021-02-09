<?php

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Profil;
use Illuminate\Support\Facades\DB;
use SimpleXMLElement;

use function array_merge;
use function compact;
use function config;
use function date;
use function env;
use function file_get_contents;
use function number_format;
use function request;
use function strtolower;
use function ucwords;
use function view;
use function years_list;

class ProfilController extends Controller
{
    /**
     * Menampilkan Halaman Profil Kecamatan
     **/


    public function LetakGeografis()
    {
        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $page_title = 'Letak Geografis';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }

        return view('pages.profil.letakgeografis', compact('page_title', 'page_description', 'profil', 'defaultProfil'));
    }

    public function StrukturPemerintahan()
    {
        $defaultProfil = config('app.default_profile');
        $profil        = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Struktur Pemerintahan';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.strukturpemerintahan', compact('page_title', 'page_description', 'profil'));
    }

    public function Kependudukan()
    {
        Counter::count('profil.kependudukan');

        $data['page_title']       = 'Kependudukan';
        $data['page_description'] = 'Statistik Kependudukan';
        $defaultProfil            = config('app.default_profile');
        $data['defaultProfil']    = $defaultProfil;
        $data['year_list']        = years_list();
        $data['list_kecamatan']   = Profil::with('kecamatan')->orderBy('kecamatan_id', 'desc')->get();
        $data['list_desa']        = DB::table('das_data_desa')->select('*')->where('kecamatan_id', '=', $defaultProfil)->get();

        $data = array_merge($data, $this->createDashboardKependudukan($defaultProfil, 'ALL', date('Y')));

        return view('pages.kependudukan.show_kependudukan')->with($data);
    }

    public function VisiMisi()
    {
        $defaultProfil = config('app.default_profile');
        $profil        = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Visi dan Misi';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.visimisi', compact('page_title', 'page_description', 'profil'));
    }

    public function sejarah()
    {
        $defaultProfil = config('app.default_profile');

        $profil     = Profil::where('kecamatan_id', $defaultProfil)->first();
        $page_title = 'Sejarah';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($profil->kecamatan->nama));
        }
        return view('pages.profil.sejarah', compact('page_title', 'page_description', 'profil'));
    }

    public function showProfile()
    {
        Counter::count('dashboard.profil');

        $defaultProfil = config('app.default_profile');

        $profil = Profil::where('kecamatan_id', $defaultProfil)->first();

        $dokumen = DB::table('das_form_dokumen')->take(5)->get();

        $page_title = 'Profil';
        if (isset($profil)) {
            $page_description = ucwords(strtolower($this->sebutan_wilayah . ' ' . $profil->kecamatan->nama));
        }

        return view('pages.profil.show_profil', compact('page_title', 'page_description', 'profil', 'defaultProfil', 'dokumen'));
    }
}
