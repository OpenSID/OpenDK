<?php

declare(strict_types=1);

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2026 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Data;

use App\Enums\JenisJabatan;
use App\Enums\Status;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengurusRequest;
use App\Models\Agama;
use App\Models\Jabatan;
use App\Models\PendidikanKK;
use App\Models\Pengurus;
use App\Traits\BaganTrait;
use App\Traits\HandlesFileUpload;
use Illuminate\Contracts\View\View;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Yajra\DataTables\DataTables;

class PengurusController extends Controller
{
    use BaganTrait;
    use HandlesFileUpload;

    /**
     * Display a listing of the resource.
     *
     * @return mixed
     */
    public function index(Request $request)
    {
        $page_title = 'Data Pengurus';
        $page_description = 'Daftar Data Pengurus';

        if ($request->ajax()) {
            $status = $request->input('status');

            return DataTables::of(Pengurus::where('status', $status))
                ->addIndexColumn()
                ->addColumn('aksi', function ($row) {
                    if (! auth()->guest()) {
                        $data['arsip_url'] = route('data.pengurus.arsip', ['pengurus_id' => $row->id]);
                        $data['edit_url'] = route('data.pengurus.edit', $row->id);
                        $data['delete_url'] = route('data.pengurus.destroy', $row->id);
                        if ($row->status == Status::Aktif) {
                            $data['suspend_url'] = route('data.pengurus.lock', [$row->id, Status::TidakAktif]);
                        } else {
                            $data['active_url'] = route('data.pengurus.lock', [$row->id, Status::Aktif]);
                        }
                    }

                    return view('forms.aksi', $data);
                })
                ->editColumn('foto', function ($row) {
                    return '<img src="'.is_user($row->foto, $row->sex, true).'" class="img-rounded" alt="Foto Penduduk" height="50"/>';
                })
                ->editColumn('identitas', function ($row) {
                    return $row->namaGelar.',<br> NIP: '.$row->nip.',<br> NIK: '.$row->nik;
                })
                ->editColumn('ttl', function ($row) {
                    return $row->tempat_lahir.','.format_date($row->tanggal_lahir);
                })
                ->editColumn('sex', function ($row) {
                    $sex = ['1' => 'Laki-laki', '2' => 'Perempuan'];

                    return $sex[$row->sex];
                })
                ->editColumn('tanggal_sk', function ($row) {
                    return format_date($row->tanggal_sk);
                })
                ->editColumn('tanggal_henti', function ($row) {
                    return format_date($row->tanggal_sk);
                })
                ->editColumn('status', function ($row) {
                    if ($row->status == 0) {
                        return '<span class="label label-danger">Tidak Aktif</span>';
                    } else {
                        return '<span class="label label-success">Aktif</span>';
                    }
                })
                ->rawColumns(['foto', 'identitas', 'status'])
                ->make(true);
        }

        return view('data.pengurus.index', compact('page_title', 'page_description'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create(): View
    {
        $page_title = 'Pengurus';
        $page_description = 'Tambah Pengurus';
        $pendidikan = PendidikanKK::pluck('nama', 'id');
        $agama = Agama::pluck('nama', 'id');
        $pengurus = new Pengurus;
        $kecuali = $pengurus->cekPengurus();
        $jabatan = Jabatan::whereNotIn('jenis', $kecuali)->pluck('nama', 'id');
        $atasan = Pengurus::ListAtasan()
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id_pengurus => "{$item->nama_pengurus} - {$item->jabatan}"];
            });

        $pengurus = [];

        return view('data.pengurus.create', compact('page_title', 'page_description', 'pendidikan', 'agama', 'jabatan', 'atasan', 'pengurus'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(PengurusRequest $request): RedirectResponse
    {
        try {
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'foto', 'pengurus', false);

            $pengurus = new Pengurus;
            $fillableFields = ['nama', 'nik', 'gelar_depan', 'gelar_belakang', 'nip', 'tempat_lahir', 'tanggal_lahir', 'sex', 'pendidikan_id', 'agama_id', 'pangkat', 'no_sk', 'tanggal_sk', 'no_henti', 'tanggal_henti', 'masa_jabatan', 'jabatan_id', 'atasan', 'bagan_tingkat', 'bagan_warna'];

            foreach ($fillableFields as $field) {
                if (isset($input[$field])) {
                    $pengurus->$field = $input[$field];
                }
            }

            $pengurus->save();
        } catch (\Exception $e) {
            Log::error('Pengurus creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'input' => $request->except(['foto']),
            ]);

            return back()->withInput()->with('error', 'Pengurus gagal ditambah!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Pengurus berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(int $id): View
    {
        $pengurus = Pengurus::findOrFail($id);
        $page_title = 'Pengurus';
        $page_description = 'Ubah Pengurus : '.$pengurus->nama;
        $pendidikan = PendidikanKK::pluck('nama', 'id');
        $agama = Agama::pluck('nama', 'id');
        $kecuali = $pengurus->cekPengurus();

        $jabatan = Jabatan::whereNotIn('jenis', $kecuali)->orWhere('jenis', $pengurus->jabatan->jenis)
            ->pluck('nama', 'id');

        $atasan = Pengurus::ListAtasan($id)
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id_pengurus => "{$item->nama_pengurus} - {$item->jabatan}"];
            });

        return view('data.pengurus.edit', compact('page_title', 'page_description', 'pengurus', 'pendidikan', 'agama', 'jabatan', 'atasan'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(PengurusRequest $request, int $id): RedirectResponse
    {
        $pengurus = Pengurus::findOrFail($id);

        try {
            $input = $request->validated();
            $this->handleFileUpload($request, $input, 'foto', 'pengurus', false);
            $pengurus->update($input);
        } catch (\Exception $e) {
            Log::error('Pengurus update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'pengurus_id' => $id,
            ]);

            return back()->withInput()->with('error', 'Pengurus gagal diubah!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Pengurus berhasil diubah!');
    }

    public function destroy(Pengurus $penguru): RedirectResponse
    {
        // dd($penguru);
        try {
            $penguru->delete();
        } catch (\Exception $e) {
            Log::error('Pengurus deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'pengurus_id' => $penguru->id,
            ]);

            return redirect()->route('data.pengurus.index')->with('error', 'Pengurus gagal dihapus!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Pengurus berhasil dihapus!');
    }

    /**
     * Update status resource from storage.
     */
    public function lock(int $id, int $status): RedirectResponse
    {
        try {
            $pengurus = Pengurus::findOrFail($id);

            if ($status == Status::Aktif) {
                if ($pengurus->jabatan->jenis == JenisJabatan::Camat && Pengurus::whereHas('jabatan', fn ($q) => $q->where('jenis', JenisJabatan::Camat))->where('status', Status::Aktif)->exists()) {
                    return redirect()->route('data.pengurus.index')->with('error', 'Camat aktif sudah ditetapkan!');
                }

                if ($pengurus->jabatan->jenis == JenisJabatan::Sekretaris && Pengurus::whereHas('jabatan', fn ($q) => $q->where('jenis', JenisJabatan::Sekretaris))->where('status', Status::Aktif)->exists()) {
                    return redirect()->route('data.pengurus.index')->with('error', 'Sekretaris aktif sudah ditetapkan!');
                }
            }

            $pengurus->status = $status;
            $pengurus->save();
        } catch (\Exception $e) {
            Log::error('Pengurus status update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'pengurus_id' => $id,
                'status' => $status,
            ]);

            return redirect()->route('data.pengurus.index')->with('error', 'Status Pengurus gagal diubah!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Status Pengurus berhasil diubah!');
    }

    public function bagan(): View
    {
        $page_title = 'Pengurus';
        $page_description = 'Bagan Pengurus';

        return view('data.pengurus.bagan', compact('page_title', 'page_description'));
    }

    public function ajaxBagan(): \Illuminate\Http\JsonResponse
    {
        return response()->json($this->getDataStrukturOrganisasi());
    }
}
