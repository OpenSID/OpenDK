<?php

namespace App\Http\Controllers\Page;

use App\Facades\Counter;
use App\Http\Controllers\Controller;
use App\Models\Profil;
use App\Models\Prosedur;
use App\Models\Regulasi;
use Illuminate\Support\Facades\DB;
use Yajra\DataTables\DataTables;

use function asset;
use function compact;
use function config;
use function request;
use function response;
use function route;
use function str_replace;
use function str_slug;
use function view;

class DownloadController extends Controller
{
    public function indexProsedur()
    {
        Counter::count('unduhan.prosedur');

        $page_title       = 'Prosedur';
        $page_description = 'Kumpulan SOP Kecamatan';
        $prosedurs        = Prosedur::latest()->paginate(10);

        return view('pages.unduhan.prosedur', compact(['page_title', 'page_description', 'prosedurs']))
            ->with('i', (request()->input('page', 1) - 1) * 5);
    }

    public function getDataProsedur()
    {
        return DataTables::of(Prosedur::select('id', 'judul_prosedur'))
            ->addColumn('action', function ($row) {
                $show_url         = route('unduhan.prosedur.show', ['nama_prosedur' => str_slug($row->judul_prosedur)]);
                $data['show_url'] = $show_url;
                return view('forms.action', $data);
            })
            ->editColumn('judul_prosedur', function ($row) {
                return $row->judul_prosedur;
            })->make();
    }

    public function showProsedur($nama_prosedur)
    {
        // $prosedur   = Prosedur::find($id);
        $prosedur   = Prosedur::where('judul_prosedur', str_replace('-', ' ', $nama_prosedur))->first();
        $page_title = 'Detail Prosedur :' . $prosedur->judul_prosedur;
        return view('pages.unduhan.prosedur_show', compact('page_title', 'prosedur'));
    }

    public function downloadProsedur($file)
    {
        $getFile = Prosedur::where('judul_prosedur', str_replace('-', ' ', $file))->firstOrFail();
        return response()->download($getFile->file_prosedur);
    }

    public function indexRegulasi()
    {
        Counter::count('unduhan.regulasi');

        $page_title       = 'Regulasi';
        $page_description = 'Kumpulan regulasi Kecamatan';
        $regulasi         = Regulasi::orderBy('id', 'asc')->paginate(10);

        $defaultProfil = config('app.default_profile');

        $profil = Profil::where(['kecamatan_id' => $defaultProfil])->first();

        return view('pages.unduhan.regulasi', compact('page_title', 'page_description', 'regulasi', 'defaultProfil', 'profil'));
    }

    public function showRegulasi($nama_regulasi)
    {
        $regulasi   = Regulasi::where('judul', str_replace('-', ' ', $nama_regulasi))->first();
        $page_title = 'Detail Regulasi :' . $regulasi->judul;
        return view('pages.unduhan.regulasi_show', compact('page_title', 'regulasi'));
    }

    public function downloadRegulasi($file)
    {
        $getFile = Regulasi::where('judul', str_replace('-', ' ', $file))->firstOrFail();
        return response()->download($getFile->file_regulasi);
    }

    public function indexFormDokumen()
    {
        Counter::count('unduhan.form-dokumen');

        $page_title       = 'Dokumen';
        $page_description = 'Kumpulan Formulir Dokumen';
        return view('pages.unduhan.form-dokumen', compact('page_title', 'page_description'));
    }

    public function getDataDokumen()
    {
        $query = DB::table('das_form_dokumen')->selectRaw('id, nama_dokumen, file_dokumen');
        return DataTables::of($query->get())
            ->addColumn('action', function ($row) {
               // $show_url = route('informasi.form-dokumen.show', $row->id);
                $download_url         = asset($row->file_dokumen);
                $data['download_url'] = $download_url;
                return view('forms.action', $data);
            })->make();
    }

    public function showDokumen($nama_dokumen)
    {
        $dokumen    = dokumen::where('judul', str_replace('-', ' ', $nama_regulasi))->first();
        $page_title = 'Detail Dokumen :' . $dokumen->judul;
        return view('pages.unduhan.dokumen_show', compact('page_title', 'dokumen'));
    }

    public function downloadDokumen($file)
    {
        $getFile = Dokumen::where('judul', str_replace('-', ' ', $file))->firstOrFail();
        return response()->download($getFile->file_dokumen);
    }
}
