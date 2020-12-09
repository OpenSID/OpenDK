<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporEpidemiPenyakit;
use App\Models\EpidemiPenyakit;
use App\Models\JenisPenyakit;
use App\Models\Profil;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

use function back;
use function compact;
use function config;
use function months_list;
use function redirect;
use function request;
use function route;
use function view;
use function years_list;

class EpidemiPenyakitController extends Controller
{
    public $nama_kecamatan;

    public function __construct()
    {
        $this->nama_kecamatan = Profil::where('kecamatan_id', config('app.default_profile'))->first()->kecamatan->nama;
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Epidemi Penyakit';
        $page_description = 'Data Epidemi Penyakit Kecamatan ' . $this->nama_kecamatan;
        return view('data.epidemi_penyakit.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAKIAKB()
    {
        return DataTables::of(EpidemiPenyakit::with(['penyakit', 'desa']))
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.epidemi-penyakit.edit', $row->id);
                $delete_url = route('data.epidemi-penyakit.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
            })
            ->rawColumns(['actions'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return Response
     */
    public function import()
    {
        $page_title       = 'Import';
        $page_description = 'Import Data Epidemi Penyakit';
        $years_list       = years_list();
        $months_list      = months_list();
        $jenis_penyakit   = JenisPenyakit::pluck('nama', 'id');
        return view('data.epidemi_penyakit.import', compact('page_title', 'page_description', 'years_list', 'months_list', 'jenis_penyakit'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'file'  => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'bulan' => 'required|unique:das_epidemi_penyakit',
            'tahun' => 'required|unique:das_epidemi_penyakit',
        ]);

        try {
            (new ImporEpidemiPenyakit($request))
                ->import($request->file('file'));
        } catch (Exception $e) {
            return back()->with('error', 'Import data gagal. ' . $e->getMessage());
        }

        return back()->with('success', 'Import data sukses.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return Response
     */
    public function edit($id)
    {
        $epidemi          = EpidemiPenyakit::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Epidemi Penyakit: ' . $epidemi->penyakit->nama;
        $jenis_penyakit   = JenisPenyakit::pluck('nama', 'id');
        return view('data.epidemi_penyakit.edit', compact('page_title', 'page_description', 'epidemi', 'jenis_penyakit'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  int $id
     * @return Response
     */
    public function update(Request $request, $id)
    {
        try {
            request()->validate([
                'jumlah_penderita' => 'required',
                'penyakit_id'      => 'required',
                'bulan'            => 'required',
                'tahun'            => 'required',
            ]);

            EpidemiPenyakit::find($id)->update($request->all());

            return redirect()->route('data.epidemi-penyakit.index')->with('success', 'Data berhasil disimpan!');
        } catch (Exception $e) {
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return Response
     */
    public function destroy($id)
    {
        try {
            EpidemiPenyakit::findOrFail($id)->delete();

            return redirect()->route('data.epidemi-penyakit.index')->with('success', 'Data sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.epidemi-penyakit.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
