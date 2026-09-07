<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 *
 * Dengan ini diberikan izin, secara gratis, kepada siapa pun yang mendapatkan salinan
 * dari perangkat lunak ini dan file dokumentasi terkait ("Aplikasi Ini"), untuk diperlakukan
 * tanpa batasan, termasuk hak untuk menggunakan, menyalin, mengubah dan/atau mendistribusikan,
 * asal tunduk pada syarat berikut:
 *
 * Pemberitahuan hak cipta di atas dan pemberitahuan izin ini harus disertakan dalam
 * setiap salinan atau bagian penting Aplikasi Ini. Barang siapa yang menghapus atau menghilangkan
 * pemberitahuan ini melanggar ketentuan lisensi Aplikasi Ini.
 *
 * PERANGKAT LUNAK INI DISEDIAKAN "SEBAGAIMANA ADANYA", TANPA JAMINAN APA PUN, BAIK TERSURAT MAUPUN
 * TERSIRAT. PENULIS ATAU PEMEGANG HAK CIPTA SAMA SEKALI TIDAK BERTANGGUNG JAWAB ATAS KLAIM, KERUSAKAN ATAU
 * KEWAJIBAN APAPUN ATAS PENGGUNAAN ATAU LAINNYA TERKAIT APLIKASI INI.
 *
 * @package    OpenDK
 * @author     Tim Pengembang OpenDesa
 * @copyright  Hak Cipta 2017 - 2024 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Exports\ExportSuplemen;
use App\Exports\ExportSuplemenTerdata;
use App\Exports\ExportSuplemenTerdataGabungan;
use App\Http\Controllers\Controller;
use App\Http\Requests\SuplemenRequest;
use App\Http\Requests\SuplemenTerdataRequest;
use App\Models\DataDesa;
use App\Models\Penduduk;
use App\Models\Suplemen;
use App\Models\SuplemenTerdata;
use App\Services\PendudukService;
use Illuminate\Contracts\View\View;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Facades\Excel;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\DataTables;

class SuplemenController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(): View
    {
        $page_title = 'Data Suplemen';
        $page_description = 'Daftar Data Suplemen';

        return view('data.data_suplemen.index', compact('page_title', 'page_description'));
    }

    /**
     * Return datatable Data Suplemen.
     */
    public function getDataSuplemen(Request $request): ?JsonResponse
    {
        if ($request->ajax()) {
            return DataTables::of(Suplemen::withCount('terdata')->get())
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    $data['detail_url'] = auth()->user()->can('access.data.data_suplemen.view') ? route('data.data-suplemen.show', $row->id) : null;

                    if (!auth()->guest()) {
                        $data['edit_url'] = auth()->user()->can('access.data.data_suplemen.edit') ? route('data.data-suplemen.edit', $row->id) : null;
                        $data['delete_url'] = auth()->user()->can('access.data.data_suplemen.delete') ? route('data.data-suplemen.destroy', $row->id) : null;
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('sasaran', function ($row) {
                    $sasaran = ['1' => 'Penduduk', '2' => 'Keluarga/KK'];

                    return $sasaran[$row->sasaran];
                })
                ->make();
        }
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $page_title = 'Data Suplemen';
        $page_description = 'Tambah Data Suplemen';

        return view('data.data_suplemen.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SuplemenRequest $request): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama']);

        try {
            Suplemen::create($data);
        } catch (\Exception $e) {
            Log::error('Suplemen creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'input' => $request->except(['_token']),
            ]);

            return back()->withInput()->with('error', 'Data Suplemen gagal ditambah!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $suplemen = Suplemen::findOrFail($id);
        $page_title = 'Data Suplemen';
        $page_description = 'Ubah Data Suplemen : ' . $suplemen->nama;

        return view('data.data_suplemen.edit', compact('page_title', 'page_description', 'suplemen'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(SuplemenRequest $request, int $id): RedirectResponse
    {
        $data = $request->validated();
        $data['slug'] = Str::slug($data['nama']);

        try {
            Suplemen::findOrFail($id)->update($data);
        } catch (\Exception $e) {
            Log::error('Suplemen update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'suplemen_id' => $id,
            ]);

            return back()->withInput()->with('error', 'Data Suplemen gagal diubah!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(int $id): RedirectResponse
    {
        if (SuplemenTerdata::where('suplemen_id', $id)->exists()) {
            return redirect()->route('data.data-suplemen.index')->with('error', 'Tidak dapat menghapus Data Suplemen yang mempunyai anggota!');
        }

        try {
            Suplemen::findOrFail($id)->delete();
        } catch (\Exception $e) {
            Log::error('Suplemen deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'suplemen_id' => $id,
            ]);

            return redirect()->route('data.data-suplemen.index')->with('error', 'Data Suplemen gagal dihapus!');
        }

        return redirect()->route('data.data-suplemen.index')->with('success', 'Data Suplemen berhasil dihapus!');
    }

    /**
     * Display the specified resource.
     */
    public function show(int $id): View
    {
        $suplemen = Suplemen::findOrFail($id);
        $sasaran = ['1' => 'Penduduk', '2' => 'Keluarga/KK'];
        $page_title = 'Anggota Suplemen';
        $page_description = 'Anggota Suplemen: ' . ucwords(strtolower($suplemen->nama));

        $view = $this->isDatabaseGabungan() ? 'data.data_suplemen.gabungan.show' : 'data.data_suplemen.show';

        return view($view, compact('page_title', 'page_description', 'suplemen', 'sasaran'));
    }

    /**
     * Return datatable Data Suplemen Terdata.
     *
     * Saat database gabungan aktif, data dari API database gabungan
     * (hasil sinkronisasi desa) digabungkan dengan data anggota lokal.
     */
    public function getDataSuplemenTerdata(Request $request, int $id_terdata): ?JsonResponse
    {
        if ($request->ajax()) {
            $desa = $request->input('desa');

            $terdata = SuplemenTerdata::with('penduduk', 'penduduk.desa')
                ->where('suplemen_id', $id_terdata)
                ->when($desa && $desa !== 'Semua', fn ($query) => $query->where('desa_id', $desa))
                ->get();

            if ($this->isDatabaseGabungan()) {
                $pendudukGabungan = $this->pendudukGabunganBatch(
                    $terdata->pluck('penduduk_id_gabungan')->filter()->values()->all()
                );

                $terdata = $terdata->map(function (SuplemenTerdata $row) use ($pendudukGabungan) {
                    if ($row->penduduk_id_gabungan && ! $row->penduduk) {
                        $penduduk = $pendudukGabungan[(int) $row->penduduk_id_gabungan] ?? null;

                        if ($penduduk) {
                            $row->setRelation('penduduk', $penduduk);
                        }
                    }

                    return $row;
                });
            }

            return DataTables::of($terdata)
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! $row instanceof SuplemenTerdata) {
                        return null;
                    }

                    if (auth()->guest()) {
                        return null;
                    }

                    $data['edit_url'] = auth()->user()->can('access.data.data_suplemen.edit') ? route('data.data-suplemen.editdetail', [$row->id, $row->suplemen_id]) : null;
                    $data['delete_url'] = auth()->user()->can('access.data.data_suplemen.delete') ? route('data.data-suplemen.destroydetail', [$row->id, $row->suplemen_id]) : null;

                    return view('forms.aksi', $data);
                })
                ->editColumn('penduduk.sex', function ($row) {
                    $sex = ['1' => 'Laki-laki', '2' => 'Perempuan'];

                    return $sex[$row->penduduk->sex] ?? '-';
                })
                ->make();
        }
    }

    public function getPenduduk(string $desa, int $suplemen): JsonResponse
    {
        $penduduk = [];

        foreach (SuplemenTerdata::get() as $data) {
            if ($data->suplemen_id == $suplemen) {
                $penduduk[] = $data->penduduk_id;
            } else {
                $penduduk[] = 0;
            }
        }

        $data = Penduduk::where('desa_id', $desa)->whereNotIn('id', $penduduk)->get();

        return response()->json($data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function createDetail(int $id_suplemen): View
    {
        $suplemen = Suplemen::findOrFail($id_suplemen);
        $sasaran = ['1' => 'Penduduk', '2' => 'Keluarga/KK'];
        $page_title = 'Anggota Suplemen';
        $page_description = 'Tambah Anggota Suplemen';
        $anggota = null;
        $isDatabaseGabungan = $this->isDatabaseGabungan();

        if ($isDatabaseGabungan) {
            $data = collect();
            $desa = collect();
            $view = 'data.data_suplemen.gabungan.create_detail';
        } else {
            $desa = DataDesa::all();
            $view = 'data.data_suplemen.create_detail';

            if ($suplemen->sasaran == 1) {
                $data = Penduduk::get();
            } else {
                $data = Penduduk::where('kk_level', 1)->get();
            }
        }

        return view($view, compact('page_title', 'page_description', 'suplemen', 'sasaran', 'data', 'desa', 'anggota', 'isDatabaseGabungan'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function storeDetail(SuplemenTerdataRequest $request): RedirectResponse
    {
        $data = $this->normalizePendudukSumber($this->resolveDesaId($request->validated()));

        try {
            SuplemenTerdata::create($data);
        } catch (\Exception $e) {
            Log::error('Suplemen Terdata creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'suplemen_id' => $request->input('suplemen_id'),
            ]);

            return back()->withInput()->with('error', 'Anggota Suplemen gagal ditambah!');
        }

        return redirect()->route('data.data-suplemen.show', $request->input('suplemen_id'))->with('success', 'Anggota Suplemen berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function editDetail(int $id, int $id_suplemen): View
    {
        $suplemen = Suplemen::findOrFail($id_suplemen);
        $sasaran = ['1' => 'Penduduk', '2' => 'Keluarga/KK'];
        $page_title = 'Anggota Suplemen';
        $page_description = 'Ubah Anggota Suplemen';
        $anggota = SuplemenTerdata::with('penduduk', 'penduduk.desa')->where('id', $id)->firstOrFail();
        $isDatabaseGabungan = $this->isDatabaseGabungan();

        if ($isDatabaseGabungan) {
            $penduduk = null;

            if ($anggota->penduduk_id_gabungan) {
                $penduduk = $this->pendudukGabunganBatch([$anggota->penduduk_id_gabungan])[(int) $anggota->penduduk_id_gabungan] ?? null;
            }

            $selectedDesaId = $penduduk?->desa?->desa_id ?? $anggota->desa_id;

            return view('data.data_suplemen.gabungan.edit_detail', compact('page_title', 'page_description', 'suplemen', 'sasaran', 'anggota', 'isDatabaseGabungan', 'penduduk', 'selectedDesaId'));
        }

        $desa = DataDesa::all();
        $data = $anggota->penduduk_id ? Penduduk::where('id', $anggota->penduduk_id)->get() : collect();
        $selectedDesaId = optional($anggota->penduduk)?->desa?->desa_id ?? $anggota->desa_id;
        $selectedPendudukId = optional($anggota->penduduk)->id;

        return view('data.data_suplemen.edit_detail', compact('page_title', 'page_description', 'suplemen', 'sasaran', 'data', 'desa', 'anggota', 'isDatabaseGabungan', 'selectedDesaId', 'selectedPendudukId'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function updateDetail(SuplemenTerdataRequest $request, int $id): RedirectResponse
    {
        $data = $this->normalizePendudukSumber($this->resolveDesaId($request->validated()));

        try {
            SuplemenTerdata::findOrFail($id)->update($data);
        } catch (\Exception $e) {
            Log::error('Suplemen Terdata update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'terdata_id' => $id,
            ]);

            return back()->withInput()->with('error', 'Anggota Suplemen gagal diubah!');
        }

        return redirect()->route('data.data-suplemen.show', $request->input('suplemen_id'))->with('success', 'Anggota Suplemen berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroyDetail(int $id, int $id_suplemen): RedirectResponse
    {
        try {
            SuplemenTerdata::findOrFail($id)->delete();
        } catch (\Exception $e) {
            Log::error('Suplemen Terdata deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'terdata_id' => $id,
                'suplemen_id' => $id_suplemen,
            ]);

            return redirect()->route('data.data-suplemen.show', $id_suplemen)->with('error', 'Anggota Suplemen gagal dihapus!');
        }

        return redirect()->route('data.data-suplemen.show', $id_suplemen)->with('success', 'Anggota Suplemen berhasil dihapus!');
    }

    /**
     * Export Excel data suplemen.
     */
    public function exportExcel(Request $request): BinaryFileResponse
    {
        $filters = $request->only(['nama', 'sasaran']);
        $timestamp = date('Y-m-d-H-i-s');
        $filename = "data-suplemen-{$timestamp}.xlsx";

        return Excel::download(new ExportSuplemen($filters), $filename);
    }

    /**
     * Export Excel data suplemen terdata.
     */
    public function exportTerdataExcel(Request $request, int $id): BinaryFileResponse
    {
        $suplemen = Suplemen::findOrFail($id);
        $filters = $request->only(['desa', 'nama_penduduk']);        
        $timestamp = date('Y-m-d-H-i-s');
        $filename = "data-suplemen-terdata-{$suplemen->slug}-{$timestamp}.xlsx";

        if ($this->isDatabaseGabungan()) {
            $query = SuplemenTerdata::query()->where('suplemen_id', $id);

            if (!empty($filters['desa'])) {
                $query->where(function ($q) use ($filters) {
                    $q->where('desa_id', $filters['desa']);
                });
            }

            if (!empty($filters['nama_penduduk'])) {
                $query->whereHas('penduduk', function ($q) use ($filters) {
                    $q->where('nama', 'like', '%' . $filters['nama_penduduk'] . '%');
                });
            }

            $pendudukGabunganIds = $query->whereNotNull('penduduk_id_gabungan')->pluck('penduduk_id_gabungan')->filter()->values()->all();            

            return Excel::download(new ExportSuplemenTerdataGabungan($id, $filters, $pendudukGabunganIds), $filename);
        }

        return Excel::download(new ExportSuplemenTerdata($id, $filters), $filename);
    }

    /**
     * Normalisasi sumber penduduk untuk anggota suplemen.
     *
     * Anggota hanya boleh berisi salah satu sumber: penduduk lokal (penduduk_id)
     * atau penduduk dari database gabungan (penduduk_id_gabungan), tidak keduanya.
     */
    private function normalizePendudukSumber(array $validated): array
    {
        if (! empty($validated['penduduk_id_gabungan'])) {
            $validated['penduduk_id'] = null;
        } else {
            $validated['penduduk_id_gabungan'] = null;
        }

        return $validated;
    }

    /**
     * Resolusi desa_id untuk anggota suplemen.
     *
     * Nilai desa_id dari form diutamakan. Fallback diambil dari respons API
     * database gabungan (config.kode_desa) atau kolom desa_id das_penduduk
     * untuk penduduk lokal.
     */
    private function resolveDesaId(array $data): array
    {
        if (! empty($data['desa_id'])) {
            return $data;
        }

        if (! empty($data['penduduk_id_gabungan'])) {
            $penduduk = $this->pendudukGabunganBatch([(int) $data['penduduk_id_gabungan']])[(int) $data['penduduk_id_gabungan']] ?? null;
            $data['desa_id'] = $penduduk?->desa?->desa_id;
        } elseif (! empty($data['penduduk_id'])) {
            $data['desa_id'] = Penduduk::where('id', $data['penduduk_id'])->value('desa_id');
        }

        return $data;
    }

    /**
     * Resolusi beberapa penduduk dari database gabungan sekaligus (batch)
     * menggunakan filter id_penduduk berupa array, agar tidak melakukan
     * request berulang per anggota.
     *
     * @param  array<int, int|string>  $ids
     *
     * @return array<int, Penduduk>
     */
    private function pendudukGabunganBatch(array $ids): array
    {
        if (empty($ids)) {
            return [];
        }

        try {
            $penduduk = [];

            foreach ((new PendudukService())->pendudukGabunganByIds($ids) as $item) {
                if (! is_array($item)) {
                    continue;
                }

                $penduduk[(int) ($item['id'] ?? 0)] = $this->mapGabunganPenduduk($item);
            }

            return $penduduk;
        } catch (\Exception $e) {
            Log::error('Penduduk batch fetch from gabungan failed', [
                'error' => $e->getMessage(),
                'ids' => $ids,
            ]);

            return [];
        }
    }

    /**
     * Map respons penduduk API database gabungan (JSON:API) ke model Eloquent
     * yang digunakan kolom datatable dan form anggota suplemen.
     */
    private function mapGabunganPenduduk(array $item): Penduduk
    {
        $attributes = $item['attributes'] ?? [];

        $penduduk = new Penduduk();
        $penduduk->forceFill([
            'id' => $item['id'] ?? null,
            'no_kk' => data_get($attributes, 'keluarga.no_kk') ?? $attributes['no_kk'] ?? null,
            'nik' => $attributes['nik'] ?? null,
            'nama' => $attributes['nama'] ?? null,
            'tempat_lahir' => $attributes['tempatlahir'] ?? $attributes['tempat_lahir'] ?? null,
            'tanggal_lahir' => $attributes['tanggallahir'] ?? $attributes['tanggal_lahir'] ?? null,
            'sex' => $attributes['sex'] ?? null,
            'alamat' => $attributes['alamat_sekarang'] ?? $attributes['alamat'] ?? null,
        ]);

        $desa = new DataDesa();
        $desa->nama = data_get($attributes, 'config.nama_desa') ?? $attributes['nama_desa'] ?? null;
        $desa->desa_id = data_get($attributes, 'config.kode_desa') ?? $attributes['kode_desa'] ?? null;
        $penduduk->setRelation('desa', $desa);

        return $penduduk;
    }
}
