<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\KategoriLembagaRequest;
use App\Models\KategoriLembaga;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class KategoriLembagaController extends Controller
{
    protected $title = 'Kategori Lembaga';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = $this->title;
        $page_description = 'Daftar ' . $this->title;
        $id_kecamatan = $this->profil->kecamatan_id;

        return view('data.lembaga_kategori.index', compact('page_title', 'page_description', 'id_kecamatan'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $kategoris = KategoriLembaga::withCount('lembaga')->get();
            return DataTables::of($kategoris)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! auth()->guest()) {
                        $data['edit_url'] = route('data.kategori-lembaga.edit', $row->id);
                        $data['delete_url'] = route('data.kategori-lembaga.destroy', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('deskripsi', function ($row) {
                    return $row->deskripsi ? $row->deskripsi : '-';
                })
                ->rawColumns(['deskripsi'])
                ->make(true);
        }
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $page_title = $this->title;
        $page_description = 'Tambah ' . $this->title;

        return view('data.lembaga_kategori.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(KategoriLembagaRequest $request)
    {
        try {
            KategoriLembaga::create($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Kategori Lembaga gagal ditambah!');
        }

        return redirect()->route('data.kategori-lembaga.index')->with('success', 'Kategori Lembaga berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori_lembaga = KategoriLembaga::findOrFail($id);
        $page_title = $this->title;
        $page_description = 'Ubah '. $this->title;

        return view('data.lembaga_kategori.edit', compact('page_title', 'page_description', 'kategori_lembaga'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(KategoriLembagaRequest $request, $id)
    {
        try {
            KategoriLembaga::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Kategori Lembaga gagal diubah!');
        }

        return redirect()->route('data.kategori-lembaga.index')->with('success', 'Kategori Lembaga berhasil diubah!');
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
            KategoriLembaga::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.kategori-lembaga.index')->with('error', 'Kategori Lembaga gagal dihapus!');
        }

        return redirect()->route('data.kategori-lembaga.index')->with('success', 'Kategori Lembaga berhasil dihapus!');
    }
}
