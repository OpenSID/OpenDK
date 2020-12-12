<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporImunisasi;
use App\Models\Imunisasi;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function months_list;
use function redirect;
use function request;
use function route;
use function view;
use function years_list;

class ImunisasiController extends Controller
{
    public $bulan;
    public $tahun;

    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function index()
    {
        $page_title       = 'Imunisasi';
        $page_description = 'Data Cakupan Imunisasi ';
        return view('data.imunisasi.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataAKIAKB()
    {
        return DataTables::of(Imunisasi::with(['desa']))
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.imunisasi.edit', $row->id);
                $delete_url = route('data.imunisasi.destroy', $row->id);

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
        $page_description = 'Import Data Cakupan Imunisasi';
        $years_list       = years_list();
        $months_list      = months_list();
        return view('data.imunisasi.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
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
            'bulan' => 'required|unique:das_imunisasi',
            'tahun' => 'required|unique:das_imunisasi',
        ]);

        try {
            (new ImporImunisasi($request))
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
        $imunisasi        = Imunisasi::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Cakupan Imunisasi: ' . $imunisasi->id;

        return view('data.imunisasi.edit', compact('page_title', 'page_description', 'imunisasi'));
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
                'cakupan_imunisasi' => 'required',
            ]);

            Imunisasi::find($id)->update($request->all());

            return redirect()->route('data.imunisasi.index')->with('success', 'Data berhasil disimpan!');
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
            Imunisasi::findOrFail($id)->delete();

            return redirect()->route('data.imunisasi.index')->with('success', 'Data sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.imunisasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
