<?php

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLembagaRequest;
use App\Http\Requests\UpdateLembagaRequest;
use App\Models\KategoriLembaga;
use App\Models\Lembaga;
use App\Models\LembagaAnggota;
use App\Models\Penduduk;
use App\Services\PendudukService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

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
            $lembagaList = Lembaga::with(['lembagaKategori', 'penduduk', 'pendudukGabungan', 'lembagaAnggota'])
                ->withCount('lembagaAnggota as jml_anggota')
                ->get();

            $pendudukGabunganIds = $lembagaList->pluck('penduduk_id_gabungan')->filter()->unique()->values()->all();

            $pendudukGabungan = collect();
            if ($this->isDatabaseGabungan() && !empty($pendudukGabunganIds)) {
                $pendudukGabungan = (new PendudukService())->pendudukGabunganByIds($pendudukGabunganIds);
            }

            return DataTables::of($lembagaList)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (!auth()->guest()) {
                        $data['detail_url'] = auth()->user()->can('access.data.lembaga.view') ? route('data.lembaga_anggota.index', $row->slug) : null;
                        $data['edit_url'] = auth()->user()->can('access.data.lembaga.edit') ? route('data.lembaga.edit', $row->id) : null;
                        $data['delete_url'] = auth()->user()->can('access.data.lembaga.delete') ? route('data.lembaga.destroy', $row->id) : null;
                    }

                    return view('forms.aksi', $data);
                })
                ->addColumn('kategori', function ($row) {
                    return $row->lembagaKategori ? $row->lembagaKategori->nama : '-';
                })
                ->addColumn('ketua', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);

                        return $penduduk ? ($penduduk['attributes']['nama'] ?? '-') : '-';
                    }

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

        if ($this->isDatabaseGabungan()) {
            $view = 'data.lembaga.gabungan.create';
            return view($view, compact('page_title', 'page_description'));
        }

        $kategoriLembagaList = KategoriLembaga::pluck('nama', 'id');
        $pendudukList = Penduduk::all()->mapWithKeys(function ($penduduk) {
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
            return [$penduduk->id => $optionText];
        });

        return view('data.lembaga.create', compact('page_title', 'page_description', 'kategoriLembagaList', 'pendudukList'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     *
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLembagaRequest $request)
    {
        $data = $request->all();

        if ($this->isDatabaseGabungan()) {
            $data['penduduk_id'] = null;
            $data['penduduk_id_gabungan'] = $data['penduduk_id_gabungan'] ?? null;
        }

        $data['slug'] = Lembaga::generateUniqueSlug($request->nama);

        try {
            DB::transaction(function () use ($data) {
                $lembaga = Lembaga::create($data);

                LembagaAnggota::create([
                    'lembaga_id' => $lembaga->id,
                    'penduduk_id' => $data['penduduk_id'],
                    'penduduk_id_gabungan' => $data['penduduk_id_gabungan'] ?? null,
                    'no_anggota' => 1,
                    'jabatan' => 1,
                    'keterangan' => 'Ketua lembaga'
                ]);
            });
        } catch (\Exception $e) {
            Log::error('Lembaga creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token']),
            ]);

            return back()->withInput()->with('error', 'Lembaga gagal ditambah!');
        }

        return redirect()->route('data.lembaga.index')->with('success', 'Lembaga berhasil ditambah!');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     *
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
     *
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $lembaga = Lembaga::findOrFail($id);
        $page_title = $this->title;
        $page_description = 'Ubah ' . $this->title;

        if ($this->isDatabaseGabungan()) {
            $view = 'data.lembaga.gabungan.edit';
            $pendudukGabungan = (new PendudukService())->pendudukGabunganByIds([$lembaga->penduduk_id_gabungan]);
            $penduduk = new Penduduk();
            if($pendudukGabungan[0]){                
                $penduduk->nama = $pendudukGabungan[0]['attributes']['nama'];
                $penduduk->nik = $pendudukGabungan[0]['attributes']['nik'];
            }
            $lembaga->setRelation('penduduk', $penduduk);  
            return view($view, compact('page_title', 'page_description', 'lembaga'));
        }

        $kategoriLembagaList = KategoriLembaga::pluck('nama', 'id');
        $pendudukList = Penduduk::all()->mapWithKeys(function ($penduduk) {
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
            return [$penduduk->id => $optionText];
        });

        return view('data.lembaga.edit', compact('page_title', 'page_description', 'lembaga', 'kategoriLembagaList', 'pendudukList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     *
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLembagaRequest $request, $id)
    {
        $data = $request->all();

        try {
            DB::transaction(function () use ($data, $request, $id) {
                $lembaga = Lembaga::findOrFail($id);

                if ($request->nama !== $lembaga->nama) {
                    $data['slug'] = Lembaga::generateUniqueSlug($request->nama);
                }

                if ($this->isDatabaseGabungan()) {
                    $data['penduduk_id'] = null;
                    $data['penduduk_id_gabungan'] = $data['penduduk_id_gabungan'] ?? null;
                }

                $lembaga->update($data);

                $anggota = LembagaAnggota::where('lembaga_id', $lembaga->id)->first();

                if ($anggota) {
                    $anggota->update([
                        'penduduk_id' => $data['penduduk_id'] ?? $lembaga->penduduk_id,
                        'penduduk_id_gabungan' => $data['penduduk_id_gabungan'] ?? $lembaga->penduduk_id_gabungan,
                    ]);
                }
            });
        } catch (\Exception $e) {
            Log::error('Lembaga update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'lembaga_id' => $id,
            ]);

            return back()->withInput()->with('error', 'Lembaga gagal diubah!');
        }

        return redirect()->route('data.lembaga.index')->with('success', 'Lembaga berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     *
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
            Log::error('Lembaga deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'lembaga_id' => $id,
            ]);

            return redirect()->route('data.lembaga.index')->with('error', 'Lembaga gagal dihapus!');
        }

        return redirect()->route('data.lembaga.index')->with('success', 'Lembaga berhasil dihapus!');
    }
}
