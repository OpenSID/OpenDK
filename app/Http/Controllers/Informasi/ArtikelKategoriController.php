<?php

namespace App\Http\Controllers\Informasi;

use App\Http\Controllers\Controller;
use App\Models\ArtikelKategori;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class ArtikelKategoriController extends Controller
{
    protected $page_title = 'Kategori';

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $page_title = $this->page_title;
        $page_description = 'Daftar Kategori';

        return view('informasi.artikel_kategori.index', compact('page_title', 'page_description'));
    }

    /* public function getDataKategori(Request $request)
    {
        if ($request->ajax()) {
            $data = ArtikelKategori::get();

            return DataTables::of($data)
                ->addColumn('aksi', function ($row) {
                    if (!auth()->guest()) {
                        $data['edit_url'] = route('informasi.artikel-kategori.edit', $row->id_kategori);
                        $data['delete_url'] = route('informasi.artikel-kategori.destroy', $row->id_kategori);
                    }
                    
                    return view('forms.aksi', $data);
                })
                ->rawColumns(['aksi'])   //merender content column dalam bentuk html
                ->make(true);
        }
    } */
    public function getDataKategori(Request $request)
    {
        if ($request->ajax()) {
            // Ambil status dari request
            $status = $request->input('status');
    
            // Buat query dasar
            $query = ArtikelKategori::query();
    
            // Jika status bukan 'All', tambahkan filter berdasarkan status
            if ($status && $status !== 'All') {
                $query->where('status', $status);
            }
    
            $data = $query->get();
    
            return DataTables::of($data)
                ->addColumn('aksi', function ($row) {
                    if (!auth()->guest()) {
                        $data['edit_url'] = route('informasi.artikel-kategori.edit', $row->id_kategori);
                        $data['delete_url'] = route('informasi.artikel-kategori.destroy', $row->id_kategori);
                    }
                    return view('forms.aksi', $data);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 'Ya') {
                        return '<span class="label label-success">Aktif</span>';
                    } else {
                        return '<span class="label label-danger">Tidak</span>';
                    }
                })
                ->rawColumns(['aksi', 'status'])
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
        $page_title = $this->page_title;
        $page_description = 'Tambah ' . $this->page_title;

        return view('informasi.artikel_kategori.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
           'nama_kategori' => "required|max:191",
           'status' => 'required',
        ]);

        try {
            ArtikelKategori::create($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Kategori gagal ditambah!');
        }

        return redirect()->route('informasi.artikel-kategori.index')->with('success', 'Kategori berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $kategori = ArtikelKategori::findOrFail($id);
        $page_title = $this->page_title;
        $page_description = 'Ubah '. $this->page_title;

        return view('informasi.artikel_kategori.edit', compact('page_title', 'page_description', 'kategori'));
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
        try {
            ArtikelKategori::findOrFail($id)->update($request->all());
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Kategori gagal diubah!');
        }

        return redirect()->route('informasi.artikel-kategori.index')->with('success', 'Kategori berhasil diubah!');
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
            ArtikelKategori::findOrFail($id)->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('informasi.artikel-kategori.index')->with('error', 'Kategori gagal dihapus!');
        }

        return redirect()->route('informasi.artikel-kategori.index')->with('success', 'Kategori berhasil dihapus!');
    }
}
