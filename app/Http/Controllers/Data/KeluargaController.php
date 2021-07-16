<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\Keluarga;
use App\Models\Penduduk;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Request as RequestFacade;
use Maatwebsite\Excel\Facades\Excel;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function ini_set;
use function redirect;
use function request;
use function route;
use function view;

class KeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Keluarga';
        $page_description = 'Data Keluarga';

        return view('data.keluarga.index', compact('page_title', 'page_description'));
    }

    /**
     * Return datatable Data Keluarga
     */

    public function getKeluarga()
    {
        return DataTables::of(Keluarga::query())
            ->addColumn('action', function ($row) {
                $show_url   = route('data.keluarga.show', $row->id);
                $data['show_url']   = $show_url;

                return view('forms.action', $data);
            })
            ->editColumn('nik_kepala', function ($row) {
                if (isset($row->nik_kepala)) {
                    $penduduk = Penduduk::where('nik', $row->nik_kepala)->first();
                    return $penduduk->nama;
                } else {
                    return '';
                }
            })->make();
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function show($id)
    {
        $page_title       = 'Detail Keluarga';
        $page_description = 'Detail Data Keluarga';
        $penduduk         = Penduduk::select(['nik', 'nama'])->get();
        $keluarga         = Keluarga::findOrFail($id);

        return view('data.keluarga.show', compact('page_title', 'page_description', 'penduduk', 'keluarga'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Import';
        $page_description = 'Import Data Keluarga';

        return view('data.keluarga.import', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function importExcel()
    {
        ini_set('max_execution_time', 300);
        if (Input::hasFile('data_file')) {
            $path = RequestFacade::file('data_file')->getRealPath();

            Excel::filter('chunk')->load($path)->chunk(1000, function ($results) {
                foreach ($results as $row) {
                    Keluarga::insert($row->toArray());
                }
            });

            $data = Excel::load($path, function ($reader) {
            })->get();

            return redirect()->route('data.keluarga.import')->with('success', 'Data Keluarga berhasil diunggah!');
        }
    }
}
