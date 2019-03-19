<?php

namespace App\Http\Controllers\Data;

use App\Models\Keluarga;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\Input;
use Excel;

class KeluargaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
        $page_title = 'Keluarga';
        $page_description = 'Data Keluarga';

        return view('data.keluarga.index', compact('page_title', 'page_description'));
    }

    /**
     *
     * Return datatable Data Keluarga
     */

    public function getKeluarga()
    {
        return DataTables::of(Keluarga::select('*')->get())
            ->addColumn('action', function ($row) {
                $edit_url = route('data.keluarga.edit', $row->id);
                $delete_url = route('data.keluarga.destroy', $row->id);

                $data['edit_url'] = $edit_url;
                $data['delete_url'] = $delete_url;

                return view('forms.action', $data);
            })
            ->editColumn('nik_kepala', function($row) {
                if(isset($row->nik_kepala)){
                    $penduduk = Penduduk::where('nik', $row->nik_kepala)->first();
                    return $penduduk->nama;
                }else{
                  return '';
                }
                
            })->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
        $page_title = 'Tambah Keluarga';
        $page_description = 'Tambah Data Keluarga';
        $penduduk = Penduduk::select(['nik', 'nama'])->get();
      
        return view('data.keluarga.create', compact('page_title', 'page_description', 'penduduk'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
        try{
            request()->validate([
                'no_kk' => 'required|max:16',
                'nik_kepala' => 'required',
                'tgl_daftar'=>'required|date',
                'tgl_cetak_kk'=>'required|date',
                'alamat' => 'required',
                'dusun' => 'required',
                'rw' => 'required',
                'rt' => 'required',
            ]);

            Keluarga::create($request->all());

            return redirect()->route('data.keluarga.index')->with('success', 'Data Keluarga berhasil disimpan!');
        }catch (Exception $e){
            return back()->withInput()->with('error', 'Data Keluarga gagal disimpan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
        $page_title = 'Edit Keluarga';
        $page_description = 'Edit Data Keluarga';
        $penduduk = Penduduk::select(['nik', 'nama'])->get();
        $keluarga = Keluarga::findOrFail($id);
      
        return view('data.keluarga.edit', compact('page_title', 'page_description', 'penduduk', 'keluarga'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
        $keluarga = Keluarga::findOrFail($id);
        $keluarga->fill($request->all());
        try{
            request()->validate([
                'no_kk' => 'required|max:16',
                'nik_kepala' => 'required',
                'tgl_daftar'=>'required|date',
                'tgl_cetak_kk'=>'required|date',
                'alamat' => 'required',
                'dusun' => 'required',
                'rw' => 'required',
                'rt' => 'required',
            ]);

            $keluarga->save();

            return redirect()->route('data.keluarga.index')->with('success', 'Data Keluarga berhasil disimpan!');
        }catch (Exception $e){
            return back()->withInput()->with('error', 'Data Keluarga gagal disimpan!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
        try {
            Keluarga::findOrFail($id)->delete();

             return redirect()->route('data.keluarga.index')->with('success', 'Data Keluarga berhasil dihapus!');
        }catch (Exception $e){
            return back()->withInput()->with('error', 'Data Keluarga gagal dihapus!');
        }
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function import()
    {
        $page_title = 'Import';
        $page_description = 'Import Data Keluarga';

        return view('data.keluarga.import', compact('page_title', 'page_description'));
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function importExcel()
    {
        ini_set('max_execution_time', 300);
        if (Input::hasFile('data_file')) {

            $path = Input::file('data_file')->getRealPath();


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
