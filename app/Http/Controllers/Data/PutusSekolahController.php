<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Imports\ImporPutusSekolah;
use App\Models\PutusSekolah;
use function back;
use function compact;
use Exception;
use Illuminate\Http\Request;

use Illuminate\Http\Response;
use function months_list;
use function redirect;
use function request;
use function route;
use function view;
use Yajra\DataTables\DataTables;
use function years_list;

class PutusSekolahController extends Controller
{
    public function __construct()
    {
        parent::__construct();
    }

    public function index()
    {
        $page_title       = 'Anak Putus Sekolah';
        $page_description = 'Data Anak Putus Sekolah ' . $this->sebutan_wilayah. ' ' .$this->nama_wilayah;
        return view('data.putus_sekolah.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return Response
     */
    public function getDataPutusSekolah()
    {
        return DataTables::of(PutusSekolah::with(['desa']))
            ->addColumn('actions', function ($row) {
                $edit_url   = route('data.putus-sekolah.edit', $row->id);
                $delete_url = route('data.putus-sekolah.destroy', $row->id);

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
        $page_description = 'Import Data Anak Putus Sekolah';
        $years_list       = years_list();
        $months_list      = months_list();
        return view('data.putus_sekolah.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function do_import(Request $request)
    {
        $this->validate($request, [
            'desa_id'  => 'required|unique:das_putus_sekolah,desa_id',
            'file'     => 'required|file|mimes:xls,xlsx,csv|max:5120',
            'tahun'    => 'required|unique:das_putus_sekolah',
            'semester' => 'required|unique:das_putus_sekolah',
        ]);

        try {
            (new ImporPutusSekolah($request->only(['desa_id', 'semester', 'tahun'])))
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
        $siswa            = PutusSekolah::findOrFail($id);
        $page_title       = 'Ubah';
        $page_description = 'Ubah Data Anak Putus Sekolah';
        return view('data.putus_sekolah.edit', compact('page_title', 'page_description', 'siswa'));
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
                'siswa_paud'     => 'required',
                'anak_usia_paud' => 'required',
                'siswa_sd'       => 'required',
                'anak_usia_sd'   => 'required',
                'siswa_smp'      => 'required',
                'anak_usia_smp'  => 'required',
                'siswa_sma'      => 'required',
                'anak_usia_sma'  => 'required',
                'bulan'          => 'required',
                'tahun'          => 'required',
            ]);

            PutusSekolah::find($id)->update($request->all());

            return redirect()->route('data.putus-sekolah.index')->with('success', 'Data berhasil disimpan!');
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
            PutusSekolah::findOrFail($id)->delete();

            return redirect()->route('data.putus-sekolah.index')->with('success', 'Data sukses dihapus!');
        } catch (Exception $e) {
            return redirect()->route('data.putus-sekolah.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
