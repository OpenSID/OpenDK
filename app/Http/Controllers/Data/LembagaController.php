<?php

namespace App\Http\Controllers\Data;

use App\Models\Lembaga;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Models\LembagaAnggota;
use App\Models\KategoriLembaga;
use Yajra\DataTables\DataTables;
use Illuminate\Support\Facades\DB;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLembagaRequest;
use App\Http\Requests\UpdateLembagaRequest;

class LembagaController extends Controller
{
    protected $title = 'Lembaga';

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

        return view('data.lembaga.index', compact('page_title', 'page_description', 'id_kecamatan'));
    }

    public function getData(Request $request)
    {
        if ($request->ajax()) {
            $lembagaList = Lembaga::with(['lembagaKategori','penduduk', 'lembagaAnggota'])
                                    ->withCount('lembagaAnggota as jml_anggota')
                                    ->get();
            return DataTables::of($lembagaList)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! auth()->guest()) {
                        $data['detail_url'] = route('data.lembaga_anggota.index', $row->slug);
                        $data['edit_url'] = route('data.lembaga.edit', $row->id);
                        $data['delete_url'] = route('data.lembaga.destroy', $row->id);
                    }

                    return view('forms.aksi', $data);
                })
                ->addColumn('kategori', function ($row) {
                    return $row->lembagaKategori ? $row->lembagaKategori->nama : '-';
                })
                ->addColumn('ketua', function ($row) {
                    return $row->penduduk ? $row->penduduk->nama : '-';
                })
                ->rawColumns(['aksi'])
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
        $kategoriLembagaList = KategoriLembaga::pluck('nama', 'id');
        $pendudukList = Penduduk::all()->mapWithKeys(function ($penduduk) {
            // Membuat string yang menggabungkan NIK, nama, dan alamat
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
        
            // Menambahkan ke array dengan 'id' sebagai key dan $optionText sebagai value
            return [$penduduk->id => $optionText];
        });

        return view('data.lembaga.create', compact('page_title', 'page_description', 'kategoriLembagaList', 'pendudukList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLembagaRequest $request)
    {
        $data = $request->all();
    
        // Menggunakan scope untuk menghasilkan slug yang unik
        $data['slug'] = Lembaga::generateUniqueSlug($request->nama);
        
        try {
            DB::beginTransaction(); // Memulai transaksi
            // Insert ke table 'lembaga'
            $lembaga = Lembaga::create($data);

            // Insert ke tabel 'lembagaanggota' dengan id penduduk yang diambil dari input
            LembagaAnggota::create([
                'lembaga_id' => $lembaga->id,
                'penduduk_id' => $data['penduduk_id'],
                'no_anggota' => 1,
                'jabatan' => 1,
                'keterangan' => 'Ketua lembaga'
            ]);

            DB::commit(); // Menyimpan transaksi jika tidak ada error
        } catch (\Exception $e) {
            DB::rollBack(); // Mengembalikan transaksi jika ada error

            report($e);
            return back()->withInput()->with('error', 'Lembaga gagal ditambah!');
        }

        return redirect()->route('data.lembaga.index')->with('success', 'Lembaga berhasil ditambah!');
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
        $lembaga = Lembaga::findOrFail($id);
        $page_title = $this->title;
        $page_description = 'Ubah '. $this->title;
        $kategoriLembagaList = KategoriLembaga::pluck('nama', 'id');
        $pendudukList = Penduduk::all()->mapWithKeys(function ($penduduk) {
            // Membuat string yang menggabungkan NIK, nama, dan alamat
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
        
            // Menambahkan ke array dengan 'id' sebagai key dan $optionText sebagai value
            return [$penduduk->id => $optionText];
        });

        return view('data.lembaga.edit', compact('page_title', 'page_description', 'lembaga', 'kategoriLembagaList', 'pendudukList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLembagaRequest $request, $id)
    {
        $data = $request->all();

        try {
            DB::beginTransaction();

            // Temukan lembaga yang akan diupdate
            $lembaga = Lembaga::findOrFail($id);

            // Hanya buat slug baru jika nama lembaga berubah
            if ($request->nama !== $lembaga->nama) {
                $data['slug'] = Lembaga::generateUniqueSlug($request->nama);
            }

            // Update data lembaga
            $lembaga->update($data);

            // Cek apakah anggota terkait sudah ada
            $anggota = LembagaAnggota::where('lembaga_id', $lembaga->id)->first();

            if ($anggota) {
                // Update penduduk_id pada anggota jika anggota sudah ada
                $anggota->update([
                    'penduduk_id' => $data['penduduk_id'] ?? $lembaga->penduduk_id,
                ]);
            }

            DB::commit();
        } catch (\Exception $e) {
            DB::rollBack();
            
            report($e);
            return back()->withInput()->with('error', 'Lembaga gagal diubah!');
        }

        return redirect()->route('data.lembaga.index')->with('success', 'Lembaga berhasil diubah!');
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
            // Cari lembaga berdasarkan ID
            $lembaga = Lembaga::findOrFail($id);

            // Hapus semua anggota terkait lembaga ini
            $lembaga->lembagaAnggota()->delete();

            // Hapus lembaga itu sendiri
            $lembaga->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.lembaga.index')->with('error', 'Lembaga gagal dihapus!');
        }

        return redirect()->route('data.lembaga.index')->with('success', 'Lembaga berhasil dihapus!');
    }
}
