<?php

namespace App\Http\Controllers\Data;

use App\Models\AnggaranRealisasi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Input;
use Yajra\DataTables\Facades\DataTables;
use Excel;


class AnggaranRealisasiController extends Controller
{
    //

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $page_title = 'Anggran & Realisasi';
        $page_description = 'Data Anggran & Realisasi Kecamatan';
        return view('data.anggaran_realisasi.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataAnggaran()
    {
        //
        return DataTables::of(AnggaranRealisasi::select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url = route('data.anggaran-realisasi.edit', $row->id);
                $delete_url = route('data.anggaran-realisasi.destroy', $row->id);

                $data['edit_url'] = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })->editColumn('bulan', function($row){
                return months_list()[$row->bulan];
            })
            ->rawColumns(['actions'])->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        //
        $page_title = 'Import';
        $page_description = 'Import Data Anggaran & Realisasi';
        $years_list = years_list();
        $months_list = months_list();
        return view('data.anggaran_realisasi.import', compact('page_title', 'page_description', 'years_list', 'months_list'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function do_import(Request $request)
    {
        //
        ini_set('max_execution_time', 300);
        $bulan = $request->input('bulan');
        $tahun = $request->input('tahun');

        request()->validate([
            'file' => 'file|mimes:xls,xlsx,csv|max:5120',
        ]);

        if ($request->hasFile('file') && $this->uploadValidation($bulan, $tahun)) {
            try{
                $path = Input::file('file')->getRealPath();

                $data = Excel::load($path, function ($reader) {
                })->get();

                if (!empty($data) && $data->count()) {


                    foreach ($data->toArray() as $key => $value) {
                        if (!empty($value)) {
                            $insert[] = [
                                'kecamatan_id' => config('app.default_profile'),
                                'total_anggaran'=> $value['total_anggaran'],
                                'total_belanja'=> $value['total_belanja'],
                                'belanja_pegawai'=> $value['belanja_pegawai'],
                                'belanja_barang_jasa'=> $value['belanja_barang_jasa'],
                                'belanja_modal'=> $value['belanja_modal'],
                                'belanja_tidak_langsung'=> $value['belanja_tidak_langsung'],
                                'bulan' => $bulan,
                                'tahun' => $tahun,
                            ];
                        }
                    }

                    if (!empty($insert)) {
                        try{
                            AnggaranRealisasi::insert($insert);
                            return back()->with('success', 'Import data sukses.');
                        }catch (QueryException $ex){
                            return back()->with('error', 'Import data gagal. '.$ex->getCode());
                        }
                    }

                }
            }catch (\Exception $ex){
                return back()->with('error', 'Import data gagal. '.$ex->getMessage());
            }

        }else{
            return back()->with('error', 'Import data gagal. Data sudah pernah diimport.');
        }
    }

    protected function uploadValidation($bulan, $tahun){
        return !AnggaranRealisasi::where('bulan',$bulan)->where('tahun', $tahun)->exists();
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $anggaran = AnggaranRealisasi::findOrFail($id);
        $page_title = 'Ubah';
        $page_description = 'Ubah Data Anggaran & Realisasi: '.$anggaran->id;

        return view('data.anggaran_realisasi.edit', compact('page_title', 'page_description', 'anggaran'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        try{
            request()->validate([
                'bulan' => 'required',
                'tahun' => 'required',
                'total_anggaran' => 'required|numeric',
                'total_belanja' => 'required|numeric',
                'belanja_pegawai' => 'required|numeric',
                'belanja_barang_jasa' => 'required|numeric',
                'belanja_modal' => 'required|numeric',
                'belanja_tidak_langsung' => 'required|numeric',
            ]);

            AnggaranRealisasi::find($id)->update($request->all());

            return redirect()->route('data.anggaran-realisasi.index')->with('success', 'Data berhasil disimpan!');
        }catch (QueryException $e){
            return back()->withInput()->with('error', 'Data gagal disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            AnggaranRealisasi::findOrFail($id)->delete();

            return redirect()->route('data.anggaran-realisasi.index')->with('success', 'Data sukses dihapus!');

        } catch (QueryException $e) {
            return redirect()->route('data.anggaran-realisasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
