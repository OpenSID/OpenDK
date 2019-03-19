<?php

namespace App\Http\Controllers\Data;

use App\Models\ToiletSanitasi;
use App\Models\Profil;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\Facades\DataTables;
use Illuminate\Support\Facades\Input;
use Excel;

class ToiletSanitasiController extends Controller
{
    public $nama_kecamatan;
    public $bulan;
    public $tahun;

    public function __construct()
    {
        $this->nama_kecamatan = Profil::where('kecamatan_id', config('app.default_profile'))->first()->kecamatan->nama;
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $page_title = 'Toilet & Sanitasi';
        $page_description = 'Data Toilet & Sanitasi Kecamatan '.$this->nama_kecamatan;
        return view('data.toilet_sanitasi.index', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getDataAKIAKB()
    {
        //
        return DataTables::of(ToiletSanitasi::with(['desa'])->select('*')->get())
            ->addColumn('actions', function ($row) {
                $edit_url = route('data.toilet-sanitasi.edit', $row->id);
                $delete_url = route('data.toilet-sanitasi.destroy', $row->id);

                $data['edit_url'] = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('desa_id', function($row){
                return $row->desa->nama;
            })
            ->editColumn('bulan', function($row){
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
        $page_description = 'Import Data Toilet & Sanitasi';
        $years_list = years_list();
        $months_list = months_list();
        return view('data.toilet_sanitasi.import', compact('page_title', 'page_description', 'kecamatan_id', 'list_desa', 'years_list', 'months_list'));
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
                            foreach ($value as $v) {
                                $insert[] = [
                                    'kecamatan_id' => config('app.default_profile'),
                                    'desa_id' => $v['desa_id'],
                                    'bulan' => $bulan,
                                    'tahun' => $tahun,
                                    'toilet' => $v['toilet'],
                                    'sanitasi' => $v['sanitasi'],
                                ];
                            }
                        }
                    }

                    if (!empty($insert)) {
                        try{
                            ToiletSanitasi::insert($insert);
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
        return !ToiletSanitasi::where('bulan',$bulan)->where('tahun', $tahun)->exists();
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
        $toilet = ToiletSanitasi::findOrFail($id);
        $page_title = 'Ubah';
        $page_description = 'Ubah Data Toilet & Sanitasi: '.$toilet->id;

        return view('data.toilet_sanitasi.edit', compact('page_title', 'page_description', 'toilet'));
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
                'toilet' => 'required',
                'sanitasi' => 'required',
            ]);

            ToiletSanitasi::find($id)->update($request->all());

            return redirect()->route('data.toilet-sanitasi.index')->with('success', 'Data berhasil disimpan!');
        }catch (Exception $e){
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
            ToiletSanitasi::findOrFail($id)->delete();

            return redirect()->route('data.toilet-sanitasi.index')->with('success', 'Data sukses dihapus!');

        } catch (Exception $e) {
            return redirect()->route('data.toilet-sanitasi.index')->with('error', 'Data gagal dihapus!');
        }
    }
}
