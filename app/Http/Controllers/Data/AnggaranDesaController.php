<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporAPBDesa;
use App\Models\AnggaranDesa;
use App\Models\DataDesa;
use Exception;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\Facades\DataTables;

use function back;
use function compact;
use function config;
use function months_list;
use function number_format;
use function redirect;
use function request;
use function route;
use function view;
use function years_list;

class AnggaranDesaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'APBDes';
        $page_description = 'Data Anggran Desa';
        return view('data.anggaran_desa.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAnggaran()
    {
        return DataTables::of(AnggaranDesa::select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.anggaran-desa.edit', $row->id);
                $delete_url = route('data.anggaran-desa.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })->editColumn('bulan', function ($row) {
                return months_list()[$row->bulan];
            })
            ->editColumn('desa_id', function ($row) {
                return $row->desa->nama;
            })
            ->editColumn('jumlah', function ($row) {
                return number_format($row->jumlah, 2);
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
        $page_description = 'Import Data Anggaran Desa';
        $years_list       = years_list();
        $months_list      = months_list();
        $list_desa        = DataDesa::where('kecamatan_id', config('app.default_profile'))->get();
        return view('data.anggaran_desa.import', compact('page_title', 'page_description', 'years_list', 'months_list', 'list_desa'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'desa'  => 'required|unique:das_anggaran_desa,desa_id',
            'file'  => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'bulan' => 'required|unique:das_anggaran_desa',
            'tahun' => 'required|unique:das_anggaran_desa',
        ]);

        try {
            (new ImporAPBDesa($request))
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
        $anggaran         = AnggaranDesa::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Anggaran Desa: ' . $anggaran->id;

        return view('data.anggaran_desa.edit', compact('page_title', 'page_description', 'anggaran'));
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
                'bulan'     => 'required',
                'tahun'     => 'required',
                'no_akun'   => 'required',
                'nama_akun' => 'required',
                'jumlah'    => 'required|numeric',
            ]);

            AnggaranDesa::find($id)->update($request->all());

            return redirect()->route('data.anggaran-desa.index')->with('success', 'Data berhasil disimpan!');
        } catch (QueryException $e) {
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
            AnggaranDesa::findOrFail($id)->delete();

            return redirect()->route('data.anggaran-desa.index')->with('success', 'Data sukses dihapus!');
        } catch (QueryException $e) {
            return redirect()->route('data.anggaran-desa.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
