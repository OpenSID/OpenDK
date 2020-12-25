<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporTingkatPendidikan;
use App\Models\TingkatPendidikan;
use App\Models\Wilayah;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;

use function back;
use function compact;
use function config;
use function months_list;
use function redirect;
use function request;
use function route;
use function view;
use function years_list;

class TingkatPendidikanController extends Controller
{

    public function __construct(){
        parent::__construct();
    }
    public function index()
    {
       
        $page_title       = 'Tingkat Pendidikan';
        $page_description = 'Data Tingkat Pendidikan ' . $this->sebutan_wilayah. ' ' .$this->nama_wilayah;
        return view('data.tingkat_pendidikan.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataTingkatPendidikan()
    {
        return DataTables::of(TingkatPendidikan::with(['desa']))
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.tingkat-pendidikan.edit', $row->id);
                $delete_url = route('data.tingkat-pendidikan.destroy', $row->id);

                $data['edit_url']   = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
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
        $page_description = 'Import Data Tingkat Pendidikan';
        $years_list       = years_list();
        $months_list      = months_list();
        return view('data.tingkat_pendidikan.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'desa_id'  => 'required|unique:das_tingkat_pendidikan,desa_id',
            'file'     => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'tahun'    => 'required|unique:das_tingkat_pendidikan',
            'semester' => 'required|unique:das_tingkat_pendidikan'
        ]);

        try {
            (new ImporTingkatPendidikan($request->all()))
                ->queue($request->file('file'));
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
        $pendidikan       = TingkatPendidikan::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Tingkat Pendidikan';
        return view('data.tingkat_pendidikan.edit', compact('page_title', 'page_description', 'pendidikan'));
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
                'tidak_tamat_sekolah'     => 'required',
                'tamat_sd'                => 'required',
                'tamat_smp'               => 'required',
                'tamat_sma'               => 'required',
                'tamat_diploma_sederajat' => 'required',
                'bulan'                   => 'required',
                'tahun'                   => 'required',
            ]);

            TingkatPendidikan::find($id)->update($request->all());

            return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data berhasil disimpan!');
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
            TingkatPendidikan::findOrFail($id)->delete();

            return redirect()->route('data.tingkat-pendidikan.index')->with('success', 'Data sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.tingkat-pendidikan.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
