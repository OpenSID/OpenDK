<?php

namespace App\Http\Controllers\Data;

use App\Models\SettingAplikasi;
use Carbon\Carbon;
use App\Models\Lembaga;
use App\Models\Penduduk;
use Illuminate\Http\Request;
use App\Models\LembagaAnggota;
use App\Services\PendudukService;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\StoreLembagaAnggotaRequest;
use App\Http\Requests\UpdateLembagaAnggotaRequest;
use Illuminate\Support\Facades\Log;

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

            $anggotaList = LembagaAnggota::with(['lembaga', 'penduduk'])
                ->where('lembaga_id', $lembaga->id)
                ->get();

            $pendudukGabunganIds = $anggotaList->pluck('penduduk_id_gabungan')->filter()->unique()->values()->all();

            $pendudukGabungan = collect();
            if ($this->isDatabaseGabungan() && !empty($pendudukGabunganIds)) {
                $pendudukGabungan = (new PendudukService())->pendudukGabunganByIds($pendudukGabunganIds);
            }
            
            return DataTables::of($anggotaList)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) use ($slug) {
                    if (!auth()->guest()) {
                        $data['edit_url'] = auth()->user()->can('access.data.lembaga.edit') ? route('data.lembaga_anggota.edit', ['slug' => $slug, 'id' => $row->id]) : null;
                        $data['delete_url'] = auth()->user()->can('access.data.lembaga.delete') ? route('data.lembaga_anggota.destroy', ['slug' => $slug, 'id' => $row->id]) : null;
                    }

                    return view('forms.aksi', $data);
                })
                ->addColumn('no_anggota', function ($row) {
                    return $row->no_anggota ?: '-';
                })
                ->addColumn('nik', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);

                        return $penduduk ? ($penduduk['attributes']['nik'] ?? '-') : '-';
                    }

                    return $row->penduduk->nik ?? '-';
                })
                ->addColumn('nama', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);

                        return $penduduk ? ($penduduk['attributes']['nama'] ?? '-') : '-';
                    }

                    return $row->penduduk->nama ?? '-';
                })
                ->addColumn('tempat_tgl_lahir', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);

                        $tempat = $penduduk ? ($penduduk['attributes']['tempatlahir'] ?? '-') : '-';
                        $tanggalLahir = $penduduk && ($penduduk['attributes']['tanggallahir'] ?? false)
                            ? Carbon::parse($penduduk['attributes']['tanggallahir'])->translatedFormat('d F Y')
                            : '-';

                        return "$tempat / $tanggalLahir";
                    }

                    $tempat = $row->penduduk->tempat_lahir ?? '-';
                    $tanggalLahir = $row->penduduk->tanggal_lahir
                        ? Carbon::parse($row->penduduk->tanggal_lahir)->translatedFormat('d F Y')
                        : '-';

                    return "$tempat / $tanggalLahir";
                })
                ->addColumn('umur', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);

                        return $penduduk['attributes']['umur'] ?? '-';
                    }

                    return $row->penduduk && $row->penduduk->tanggal_lahir
                        ? Carbon::parse($row->penduduk->tanggal_lahir)->age
                        : '-';
                })
                ->addColumn('sex', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);
                        $sex = ['1' => 'Laki-laki', '2' => 'Perempuan'];
                        return $penduduk ? ($sex[$penduduk['attributes']['sex']] ?? '-') : '-';
                    }

                    return $row->penduduk && $row->penduduk->pendudukSex ? $row->penduduk->pendudukSex->nama : '-';
                })
                ->addColumn('alamat', function ($row) use ($pendudukGabungan) {
                    if ($this->isDatabaseGabungan()) {
                        $penduduk = $pendudukGabungan->firstWhere('id', $row->penduduk_id_gabungan);

                        if (!$penduduk) {
                            return '-';
                        }

                        $alamat = $penduduk['attributes']['alamat_wilayah'] ?? '-';

                        return $alamat;
                    }

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
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

        if ($this->isDatabaseGabungan()) {
            $page_title = 'Tambah Anggota Lembaga ' . $lembaga->nama;
            $page_description = 'Tambah Anggota Lembaga ' . $lembaga->nama;

            return view('data.lembaga_anggota.gabungan.create', compact('page_title', 'page_description', 'lembaga'));
        }

        $existingAnggotaIds = $lembaga->lembagaAnggota->pluck('penduduk_id')->toArray();

        $pendudukList = Penduduk::whereNotIn('id', $existingAnggotaIds)->get()->mapWithKeys(function ($penduduk) {
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
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

        if ($request->jabatan_id == 1) {
            $existingKetua = $lembaga->lembagaAnggota()->where('jabatan', 1)->first();

            if ($existingKetua) {
                $existingKetua->update(['jabatan' => 5]);
            }
        }

        $data = $request->validated();

        if (SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->value('value') === '1') {
            $data['penduduk_id'] = null;
            $data['penduduk_id_gabungan'] = $data['penduduk_id_gabungan'] ?? null;
        }

        try {
            $lembaga->lembagaAnggota()->create($data + [
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
            Log::error('Lembaga Anggota creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'lembaga_slug' => $slug,
                'penduduk_id' => $request->penduduk_id,
            ]);

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
        $lembaga = Lembaga::where('slug', $slug)->firstOrFail();
        $anggota = LembagaAnggota::with('penduduk')->where('lembaga_id', $lembaga->id)->findOrFail($id);

        if ($this->isDatabaseGabungan()) {
            $page_title = 'Ubah Anggota Lembaga ' . $lembaga->nama;
            $page_description = 'Ubah Anggota Lembaga ' . $lembaga->nama;

            $pendudukGabungan = (new PendudukService())->pendudukGabunganByIds([$anggota->penduduk_id_gabungan]);
            $penduduk = new Penduduk();
            if($pendudukGabungan[0]){                
                $penduduk->nama = $pendudukGabungan[0]['attributes']['nama'];
                $penduduk->nik = $pendudukGabungan[0]['attributes']['nik'];
            }
            $anggota->setRelation('penduduk', $penduduk);  

            return view('data.lembaga_anggota.gabungan.edit', compact('page_title', 'page_description', 'lembaga', 'anggota'));
        }

        $existingAnggotaIds = $lembaga->lembagaAnggota()
            ->where('id', '!=', $anggota->id)
            ->pluck('penduduk_id')
            ->toArray();

        $pendudukList = Penduduk::whereNotIn('id', $existingAnggotaIds)->get()->mapWithKeys(function ($penduduk) {
            $optionText = "NIK: {$penduduk->nik} - {$penduduk->nama} - Dusun {$penduduk->dusun} RT {$penduduk->rt} / RW {$penduduk->rw}";
            return [$penduduk->id => $optionText];
        });

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
            $lembaga = Lembaga::where('slug', $slug)->firstOrFail();

            $anggota = LembagaAnggota::where('id', $id)
                ->where('lembaga_id', $lembaga->id)
                ->firstOrFail();

            if ($request->jabatan_id == 1) {
                $existingKetua = $lembaga->lembagaAnggota()->where('jabatan', 1)->first();

                if ($existingKetua && $existingKetua->id !== $anggota->id) {
                    $existingKetua->update(['jabatan' => 5]);
                }
            }

            $data = $request->validated();

            if (SettingAplikasi::where('key', 'sinkronisasi_database_gabungan')->value('value') === '1') {
                $data['penduduk_id'] = null;
                $data['penduduk_id_gabungan'] = $data['penduduk_id_gabungan'] ?? null;
            }

            $anggota->update($data + [
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
            Log::error('Lembaga Anggota update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'lembaga_slug' => $slug,
                'anggota_id' => $id,
            ]);

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
            Log::error('Lembaga Anggota deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'lembaga_slug' => $slug,
                'anggota_id' => $id,
            ]);

            return redirect()->route('data.lembaga_anggota.index', $slug)->with('error', 'Anggota Lembaga gagal dihapus!');
        }

        return redirect()->route('data.lembaga_anggota.index', $slug)->with('success', 'Anggota Lembaga berhasil dihapus!');
    }
}
