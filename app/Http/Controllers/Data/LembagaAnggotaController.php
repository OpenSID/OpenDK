<?php

namespace App\Http\Controllers\Data;

use Carbon\Carbon;
use App\Models\Lembaga;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Models\LembagaAnggota;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLembagaAnggotaRequest;
use App\Http\Requests\UpdateLembagaAnggotaRequest;

class LembagaAnggotaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        // Cari lembaga berdasarkan slug
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

        $anggota = $lembaga->lembagaAnggota;

        $page_title = 'Anggota Lembaga  ' . $lembaga->nama;
        $page_description = 'Daftar Anggota Lembaga  ' . $lembaga->nama;

        return view('data.lembaga_anggota.index', compact('page_title', 'page_description', 'anggota', 'lembaga'));
    }

    public function getData(Request $request, $slug)
    {
        if ($request->ajax()) {
            $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

            // Ambil data anggota lembaga dengan informasi lembaga dan penduduk terkait
            $anggotaList = LembagaAnggota::with(['lembaga', 'penduduk'])
                ->where('lembaga_id', $lembaga->id)
                ->get();

            return DataTables::of($anggotaList)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) use ($slug) {
                    if (!auth()->guest()) {
                        $data['edit_url'] =  route('data.lembaga_anggota.edit', ['slug' => $slug, 'id' => $row->id]);
                        $data['delete_url'] = route('data.lembaga_anggota.destroy', ['slug' => $slug, 'id' => $row->id]);
                    }

                    return view('forms.aksi', $data);
                })
                ->addColumn('no_anggota', function ($row) {
                    return $row->no_anggota ?: '-';
                })
                ->addColumn('nik', function ($row) {
                    return $row->penduduk->nik ?? '-';
                })
                ->addColumn('nama', function ($row) {
                    return $row->penduduk->nama ?? '-';
                })
                ->addColumn('tempat_tgl_lahir', function ($row) {
                    $tempat = $row->penduduk->tempat_lahir ?? '-';
                    $tanggalLahir = $row->penduduk->tanggal_lahir 
                        ? Carbon::parse($row->penduduk->tanggal_lahir)->translatedFormat('d F Y')
                        : '-';
                    return "$tempat / $tanggalLahir";
                })
                ->addColumn('umur', function ($row) {
                    return $row->penduduk && $row->penduduk->tanggal_lahir
                        ? \Carbon\Carbon::parse($row->penduduk->tanggal_lahir)->age
                        : '-';
                })
                ->addColumn('sex', function ($row) {
                    return $row->penduduk && $row->penduduk->pendudukSex ? $row->penduduk->pendudukSex->nama : '-';
                })
                ->addColumn('alamat', function ($row) {
                    $rt = $row->penduduk->rt ?? '-';
                    $rw = $row->penduduk->rw ?? '-';
                    $alamat = $row->penduduk->dusun ?? '-';
                    return "RT $rt / RW $rw $alamat";
                })
                ->addColumn('jabatan', function ($row) {
                    $jabatan = $row->jabatan;
                    switch ($jabatan) {
                        case 1:
                            return 'Ketua';
                        case 2:
                            return 'Wakil Ketua';
                        case 3:
                            return 'Sekretaris';
                        case 4:
                            return 'Bendahara';
                        default:
                            return 'Anggota';
                    }
                })
                ->addColumn('no_sk_jabatan', function ($row) {
                    return $row->no_sk_jabatan ?: '-';
                })
                ->addColumn('no_sk_pengangkatan', function ($row) {
                    return $row->no_sk_pengangkatan ?: '-';
                })
                ->addColumn('tgl_sk_pengangkatan', function ($row) {
                    return $row->tgl_sk_pengangkatan 
                        ? Carbon::parse($row->tgl_sk_pengangkatan)->format('d M Y')
                        : '-';
                })
                ->addColumn('no_sk_pemberhentian', function ($row) {
                    return $row->no_sk_pemberhentian ?: '-';
                })
                ->addColumn('tgl_sk_pemberhentian', function ($row) {
                    return $row->tgl_sk_pemberhentian 
                        ? Carbon::parse($row->tgl_sk_pemberhentian)->format('d M Y')
                        : '-';
                })
                ->addColumn('periode', function ($row) {
                    return $row->periode ?: '-';
                })
                ->addColumn('keterangan', function ($row) {
                    return $row->keterangan ?: '-';
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
    public function create($slug)
    {
        // Cari lembaga berdasarkan slug
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

        // Ambil anggota yang sudah ada di lembaga ini
        $existingAnggotaIds = $lembaga->lembagaAnggota->pluck('penduduk_id')->toArray();

        // Ambil semua penduduk yang belum menjadi anggota
        $pendudukList = Penduduk::whereNotIn('id', $existingAnggotaIds)->get()->mapWithKeys(function ($penduduk) {
            // Membuat string yang menggabungkan NIK, nama, dan alamat
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
            
            return [$penduduk->id => $optionText];
        });

        $page_title = 'Tambah Anggota Lembaga ' . $lembaga->nama;
        $page_description = 'Tambah Anggota Lembaga ' . $lembaga->nama;

        return view('data.lembaga_anggota.create', compact('page_title', 'page_description', 'lembaga', 'pendudukList'));
    }


    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(StoreLembagaAnggotaRequest $request, $slug)
    {
        // Cari lembaga berdasarkan slug
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

        // Cek apakah jabatan yang dipilih adalah Ketua (jabatan_id = 1)
        if ($request->jabatan_id == 1) {
            // Cek apakah sudah ada Ketua di lembaga ini
            $existingKetua = $lembaga->lembagaAnggota()->where('jabatan', 1)->first();

            // Jika ada, ubah jabatan ketua yang sudah ada menjadi Anggota (jabatan_id = 5)
            if ($existingKetua) {
                $existingKetua->update(['jabatan' => 5]);
            }
        }

        try {
            
            $lembaga->lembagaAnggota()->create([
                'penduduk_id' => $request->penduduk_id,
                'no_anggota' => $request->no_anggota,
                'jabatan' => $request->jabatan_id,
                'no_sk_jabatan' => $request->no_sk_jabatan,
                'no_sk_pengangkatan' => $request->no_sk_pengangkatan,
                'tgl_sk_pengangkatan' => $request->tgl_sk_pengangkatan,
                'no_sk_pemberhentian' => $request->no_sk_pemberhentian,
                'tgl_sk_pemberhentian' => $request->tgl_sk_pemberhentian,
                'periode' => $request->periode,
                'keterangan' => $request->keterangan,
            ]);
            
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Anggota Lembaga gagal ditambah!');
        }

        return redirect()->route('data.lembaga_anggota.index', $slug)->with('success', 'Anggota Lembaga berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($slug, $id)
    {
        // Cari lembaga berdasarkan slug
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

        // Cari anggota lembaga berdasarkan ID
        $anggota = LembagaAnggota::with('penduduk')->where('lembaga_id', $lembaga->id)->findOrFail($id);

        // Ambil semua penduduk yang belum menjadi anggota kecuali yang sedang diedit
        $existingAnggotaIds = $lembaga->lembagaAnggota()
            ->where('id', '!=', $anggota->id)
            ->pluck('penduduk_id')
            ->toArray();

        $pendudukList = Penduduk::whereNotIn('id', $existingAnggotaIds)->get()->mapWithKeys(function ($penduduk) {
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
            return [$penduduk->id => $optionText];
        });

        // Tambahkan penduduk saat ini (yang sedang diedit) ke daftar pendudukList
        $pendudukList->prepend("NIK: {$anggota->penduduk->nik} - {$anggota->penduduk->nama} - Dusun {$anggota->penduduk->dusun} RT {$anggota->penduduk->rt} / RW {$anggota->penduduk->rw}", $anggota->penduduk->id);

        $page_title = 'Ubah Anggota Lembaga ' . $lembaga->nama;
        $page_description = 'Ubah Anggota Lembaga ' . $lembaga->nama;

        return view('data.lembaga_anggota.edit', compact('page_title', 'page_description', 'lembaga', 'anggota', 'pendudukList'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(UpdateLembagaAnggotaRequest $request, $slug, $id)
    {
        try {
        
            // Cari lembaga berdasarkan slug
            $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

            // Cari anggota berdasarkan id dan pastikan anggota tersebut milik lembaga yang sesuai
            $anggota = LembagaAnggota::where('id', $id)
                        ->where('lembaga_id', $lembaga->id)
                        ->firstOrFail();

            // Cek apakah jabatan yang dipilih adalah Ketua (jabatan_id = 1)
            if ($request->jabatan_id == 1) {
                // Cek apakah sudah ada Ketua di lembaga ini
                $existingKetua = $lembaga->lembagaAnggota()->where('jabatan', 1)->first();

                // Jika ada Ketua lain, ubah jabatan Ketua yang sudah ada menjadi Anggota (jabatan_id = 5)
                if ($existingKetua && $existingKetua->id !== $anggota->id) {
                    $existingKetua->update(['jabatan' => 5]);
                }
            }

            // Update data anggota
            $anggota->update([
                'no_anggota' => $request->no_anggota,
                'jabatan' => $request->jabatan_id,
                'no_sk_jabatan' => $request->no_sk_jabatan,
                'no_sk_pengangkatan' => $request->no_sk_pengangkatan,
                'tgl_sk_pengangkatan' => $request->tgl_sk_pengangkatan,
                'no_sk_pemberhentian' => $request->no_sk_pemberhentian,
                'tgl_sk_pemberhentian' => $request->tgl_sk_pemberhentian,
                'periode' => $request->periode,
                'keterangan' => $request->keterangan,
            ]);

        } catch (\Exception $e) {

            report($e);
            return back()->withInput()->with('error', 'Anggota Lembaga gagal diubah!');
        }

        return redirect()->route('data.lembaga_anggota.index', $slug)->with('success', 'Anggota Lembaga berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($slug, $id)
    {
        try {
            // Cari lembaga berdasarkan ID
            $lembagaAnggota = LembagaAnggota::findOrFail($id);

            $lembagaAnggota->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.lembaga_anggota.index', $slug)->with('error', 'Anggota Lembaga gagal dihapus!');
        }

        return redirect()->route('data.lembaga_anggota.index', $slug)->with('success', 'Anggota Lembaga berhasil dihapus!');
    }
}
