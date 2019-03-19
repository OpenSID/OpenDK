<?php

namespace App\Http\Controllers\Informasi;

use App\Models\Potensi;
use Illuminate\Database\QueryException;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Counter;
use Yajra\DataTables\Facades\DataTables;
use Sentinel;
use Illuminate\Support\Facades\DB;

class PotensiController extends Controller
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
        Counter::count('informasi.potensi.index');

        $page_title = 'Potensi';
        $page_description = 'Potensi-Potensi Kecamatan';
        $potensis = DB::table('das_potensi')->simplePaginate(10);

        return view('informasi.potensi.index', compact(['page_title', 'page_description', 'potensis']));
    }

    //
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function kategori()
    {
        //
        Counter::count('informasi.potensi.kategori');

        $page_title = 'Potensi';
        $page_description = 'Potensi-Potensi Kecamatan';
        if($_GET['id'] != null){
          $potensis = DB::table('das_potensi')->where('kategori_id', $_GET['id'])->simplePaginate(10);
        }else{
          $potensis = DB::table('das_potensi')->simplePaginate(10);
        }


        return view('informasi.potensi.index', compact(['page_title', 'page_description', 'potensis']));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = 'Tambah Potensi';
        return view('informasi.potensi.create', compact('page_title'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        try{

            request()->validate([
                'kategori_id' => 'required',
                'nama_potensi' => 'required',
                'deskripsi' => 'required',
                'file_gambar' => 'image|mimes:bmp,jpg,jpeg,gif,png|max:1024'
            ]);
            $potensi = new Potensi($request->input());

            if ($request->hasFile('file_gambar')) {
                $lampiran = $request->file('file_gambar');
                $fileName = $lampiran->getClientOriginalName();
                $path = "storage/potensi_kecamatan/";
                $request->file('file_gambar')->move($path, $fileName);
                $potensi->file_gambar = $path . $fileName;
            }

            $potensi->save();

            return redirect()->route('informasi.potensi.index')->with('success', 'Potensi berhasil disimpan!');
        }catch (QueryException $e){
            return back()->withInput()->with('error', 'Simpan Event gagal! '.$e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $potensi = Potensi::find($id);
        $page_title = 'Potensi :' . $potensi->nama_potensi;

        return view('informasi.potensi.show', compact('page_title', 'potensi'));
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
        $potensi = Potensi::findOrFail($id);
        $page_title = 'Ubah';
        $page_description = 'Ubah Potensi : '. $potensi->nama_potensi;

        return view('informasi.potensi.edit', compact('page_title', 'page_description', 'potensi'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function download($id)
    {
        //
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
              'kategori_id' => 'required',
              'nama_potensi' => 'required',
              'deskripsi' => 'required',
              'file_gambar' => 'image|mimes:bmp,jpg,jpeg,gif,png|max:1024'
            ]);

            $potensi = Potensi::findOrFail($id);
            $potensi->fill($request->all());



            if ($request->hasFile('file_gambar')) {
                $lampiran = $request->file('file_gambar');
                $fileName = $lampiran->getClientOriginalName();
                $path = "storage/potensi_kecamatan/";
                $request->file('file_gambar')->move($path, $fileName);
                $potensi->file_gambar = $path . $fileName;
            }

            $potensi->save();

            return redirect()->route('informasi.potensi.index')->with('success', 'Data Potensi berhasil disimpan!');
        }catch (Exception $e){
            return back()->with('error', 'Data Potensi gagal disimpan!'.$e->getMessage());
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
        Potensi::find($id)->delete();
        return redirect()->route('informasi.potensi.index')->with('success', 'Potensi Berhasil dihapus!');
    }

    /**
     *
     * Get datatable
     */
    // public function getDataPotensi()
    // {
    //     return DataTables::of(Potensi::select('id', 'nama_potensi', 'lokasi'))
    //         ->addColumn('action', function($row){
    //             $show_url = route('informasi.potensi.show', $row->id);
    //             $edit_url = route('informasi.potensi.edit', $row->id);
    //             $delete_url = route('informasi.potensi.destroy', $row->id);
    //
    //             $data['show_url'] = $show_url;
    //
    //             if(!Sentinel::guest()){
    //                 $data['edit_url'] = $edit_url;
    //                 $data['delete_url'] = $delete_url;
    //             }
    //
    //             return view('forms.action', $data);
    //         })->make();
    // }
}
