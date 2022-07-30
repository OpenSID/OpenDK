<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\Suplemen;
use App\Models\SuplemenTerdata;
use Illuminate\Http\Request;
use Illuminate\Support\Str;
use Yajra\DataTables\DataTables;

class SuplemenController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title       = 'Data Suplemen';
        $page_description = 'Daftar Data Suplemen';

        return view('data.data_suplemen.index', compact('page_title', 'page_description'));
    }

    /**
     * Return datatable Data Suplemen
     */
    public function getDataSuplemen()
    {
        if (request()->ajax()) {
            return DataTables::of(Suplemen::withCount('terdata')->get())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $data['detail_url'] = route('data.data-suplemen.show', $row->id);

                    if (! auth()->guest()) {
                        $data['edit_url']   = route('data.data-suplemen.edit', $row->id);
                        $data['delete_url'] = route('data.data-suplemen.destroy', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('sasaran', function ($row) {
                    $sasaran = ['1' => 'Penduduk', '2' => 'Keluarga/KK', '3' => 'Desa'];
                    return $sasaran[$row->sasaran];
                })
                ->make();
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title       = 'Data Suplemen';
        $page_description = 'Tambah Data Suplemen';

        return view('data.data_suplemen.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        request()->validate([
            'nama' => 'required',
        ]);

        $request['slug'] = Str::slug($request->nama);

        try {
            Suplemen::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Suplemen gagal ditambah!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $suplemen         = Suplemen::findOrFail($id);
        $page_title       = 'Data Suplemen';
        $page_description = 'Ubah Data Suplemen : ' . $suplemen->nama;

        return view('data.data_suplemen.edit', compact('page_title', 'page_description', 'suplemen'));
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
        request()->validate([
            'nama' => 'required',
        ]);

        $request['slug'] = Str::slug($request->nama);

        try {
            Suplemen::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Data Suplemen gagal diubah!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            Suplemen::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.data-suplemen.index')->with('error', 'Data Suplemen gagal dihapus!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil dihapus!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $suplemen         = Suplemen::findOrFail($id);
        $sasaran          = ['1' => 'Penduduk', '2' => 'Keluarga/KK', '3' => 'Desa'];
        $page_title       = 'Anggota Suplemen';
        $page_description = 'Anggota Suplemen: ' . ucwords(strtolower($suplemen->nama));

        return view('data.data_suplemen.show', compact('page_title', 'page_description', 'suplemen', 'sasaran'));
    }

    /**
     * Return datatable Data Suplemen Terdata
     */
    public function getDataSuplemenTerdata($id_terdata)
    {
        if (request()->ajax()) {
            return DataTables::of(SuplemenTerdata::with('penduduk', 'penduduk.desa')->where('suplemen_id', $id_terdata)->get())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! auth()->guest()) {
                        $data['edit_url']   = route('data.data-suplemen.editdetail', [$row->id, $row->suplemen_id]);
                        $data['delete_url'] = route('data.data-suplemen.destroydetail', [$row->id, $row->suplemen_id]);
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('penduduk.sex', function ($row) {
                    $sex = ['1' => 'Laki-laki', '2' => 'Perempuan'];
                    return $sex[$row->penduduk->sex];
                })
                ->make();
        }
    }

    public function getPenduduk($desa)
    {
        $data = Penduduk::where('desa_id', $desa)->get();
        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function createDetail($id_suplemen)
    {
        $suplemen         = Suplemen::findOrFail($id_suplemen);
        $sasaran          = ['1' => 'Penduduk', '2' => 'Keluarga/KK', '3' => 'Desa'];
        $page_title       = 'Anggota Suplemen';
        $page_description = 'Tambah Anggota Suplemen : ' . $suplemen->nama;
        $desa             = null;
        $anggota          = null;
        
        if ($suplemen->sasaran == 1) {
            $data = Penduduk::pluck('nama', 'id');
        } else if (($suplemen->sasaran == 2)) {
            $data = Penduduk::where('kk_level', 1)->pluck('nama', 'id');
        } else {
            $desa = DataDesa::all();
            $data = Penduduk::all();
        }

        return view('data.data_suplemen.create_detail', compact('page_title', 'page_description', 'suplemen', 'sasaran', 'data', 'desa', 'anggota'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function storeDetail(Request $request)
    {
        request()->validate(
            ['penduduk_id' => 'required',],
            ['penduduk_id.required' => 'isian warga atau penduduk wajib diisi']
        );

        try {
            SuplemenTerdata::create($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Anggota Suplemen gagal ditambah!');
        }

        return redirect()->route('data.data-suplemen.show', $request['suplemen_id'])->with('success', 'Anggota Suplemen berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function editDetail($id, $id_suplemen)
    {
        $suplemen         = Suplemen::findOrFail($id_suplemen);
        $sasaran          = ['1' => 'Penduduk', '2' => 'Keluarga/KK', '3' => 'Desa'];
        $page_title       = 'Anggota Suplemen';
        $page_description = 'Ubah Anggota Suplemen : ' . $suplemen->nama;
        $anggota          = SuplemenTerdata::with('penduduk', 'penduduk.desa')->where('id', $id)->first();
        $data             = Penduduk::where('id', $anggota->penduduk_id)->pluck('nama', 'id');
        $desa             = null;

        if ($suplemen->sasaran == 3) {
            $desa = DataDesa::all();
            $data = Penduduk::all();
        }

        return view('data.data_suplemen.edit_detail', compact('page_title', 'page_description', 'suplemen', 'sasaran', 'data', 'desa', 'anggota'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function updateDetail(Request $request, $id)
    {
        try {
            SuplemenTerdata::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Anggota Suplemen gagal diubah!');
        }

        return redirect()->route('data.data-suplemen.show', $request['suplemen_id'])->with('success', 'Anggota Suplemen berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroyDetail($id, $id_suplemen)
    {
        try {
            SuplemenTerdata::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);
            return redirect()->route('data.data-suplemen.show', $id_suplemen)->with('error', 'Anggota Suplemen gagal dihapus!');
        }

        return redirect()->route('data.data-suplemen.show', $id_suplemen)->with('success', 'Anggota Suplemen berhasil dihapus!');
    }
}
