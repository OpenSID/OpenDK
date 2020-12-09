<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Profil;
use App\Models\Prosedur;
use App\Models\Regulasi;
use App\Models\FormDokumen;
class SitemapController extends Controller
{
    public function index()
    {
        $profil = Profil::orderBy('updated_at','DESC')->first();
        $prosedur = Prosedur::orderBy('updated_at', 'DESC')->first();
        $regulasi = Regulasi::orderBy('updated_at', 'DESC')->first();
        $dokumen = FormDokumen::orderBy('updated_at', 'DESC')->first();

        return response()->view('sitemap.index', [
            'profil' => $profil,
            'prosedur' => $prosedur,
            'regulasi' => $regulasi,
            'dokumen' => $dokumen
        ])->header('Content-Type', 'text/xml');
    }

    public function prosedur()
    {
        $prosedurs = Prosedur::all();
        return response()->view('sitemap.prosedur', [
            'prosedurs' => $prosedurs
        ])->header('Content-Type', 'text/xml');
    }
}
