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

use App\Enums\Status;
use App\Models\Agama;
use App\Models\Jabatan;
use App\Models\Pengurus;
use App\Enums\JenisJabatan;
use App\Models\PendidikanKK;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengurusRequest;
use App\Traits\BaganTrait;
use Exception;

class PengurusController extends Controller
{
    use HandlesFileUpload, BaganTrait;

    /**
     * Display a listing of the resource.
     *
     * @return Response
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
                    return '<img src="' . is_user($row->foto, $row->sex, true) . '" class="img-rounded" alt="Foto Penduduk" height="50"/>';
                })
                ->editColumn('identitas', function ($row) {
                    return $row->namaGelar . ',<br> NIP: ' . $row->nip . ',<br> NIK: ' . $row->nik;
                })
                ->editColumn('ttl', function ($row) {
                    return $row->tempat_lahir . ',' . format_date($row->tanggal_lahir);
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
     *
     * @return Response
     */
    public function create()
    {
        $page_title = 'Pengurus';
        $page_description = 'Tambah Pengurus';
        $pendidikan = PendidikanKK::pluck('nama', 'id');
        $agama = Agama::pluck('nama', 'id');
        $pengurus = new Pengurus();
        $kecuali = $pengurus->cekPengurus();
        $jabatan = Jabatan::whereNotIn('id', $kecuali)->pluck('nama', 'id');
        $atasan = Pengurus::ListAtasan()
            ->get()
            ->mapWithKeys(function ($item) {
                return [$item->id_pengurus => "{$item->nama_pengurus} - {$item->jabatan}"];
            });

        return view('data.pengurus.create', compact('page_title', 'page_description', 'pendidikan', 'agama', 'jabatan', 'atasan'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @return Response
     */
    public function store(PengurusRequest $request)
    {
        try {
            $input = $request->all();
            $this->handleFileUpload($request, $input, 'foto', 'pengurus', false);
            Pengurus::create($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Pengurus gagal ditambah!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Pengurus berhasil ditambah!');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return Response
     */
    public function edit($id)
    {
        $pengurus = Pengurus::findOrFail($id);
        $page_title = 'Pengurus';
        $page_description = 'Ubah Pengurus : ' . $pengurus->nama;
        $pendidikan = PendidikanKK::pluck('nama', 'id');
        $agama = Agama::pluck('nama', 'id');
        $kecuali = $pengurus->cekPengurus();

        $jabatan = Jabatan::whereNotIn('id', $kecuali)->orWhere('jenis', $pengurus->jabatan->jenis)
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
     *
     * @param  int  $id
     * @return Response
     */
    public function update(PengurusRequest $request, $id)
    {
        $pengurus = Pengurus::findOrFail($id);

        try {
            $input = $request->all();
            $this->handleFileUpload($request, $input, 'foto', 'pengurus', false);
            $pengurus->update($input);
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Pengurus gagal diubah!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Pengurus berhasil diubah!');
    }

    public function destroy(Pengurus $penguru)
    {
        // dd($penguru);
        try {
            $penguru->delete();
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.pengurus.index')->with('error', 'Pengurus gagal dihapus!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Pengurus berhasil dihapus!');
    }

    /**
     * Update status resource from storage.
     *
     * @param  int  $id
     * @return Response
     */
    public function lock($id, $status)
    {
        try {
            $pengurus = Pengurus::findOrFail($id);

            if ($status == Status::Aktif) {
                if ($pengurus->jabatan->id == JenisJabatan::Camat && Pengurus::where('jabatan_id', JenisJabatan::Camat)->where('status', Status::Aktif)->exists()) {
                    return redirect()->route('data.pengurus.index')->with('error', 'Camat aktif sudah ditetapkan!');
                }

                if ($pengurus->jabatan->id == JenisJabatan::Sekretaris && Pengurus::where('jabatan_id', JenisJabatan::Sekretaris)->where('status', Status::Aktif)->exists()) {
                    return redirect()->route('data.pengurus.index')->with('error', 'Sekretaris aktif sudah ditetapkan!');
                }
            }

            $pengurus->update(['status' => $status]);
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('data.pengurus.index')->with('error', 'Status Pengurus gagal diubah!');
        }

        return redirect()->route('data.pengurus.index')->with('success', 'Status Pengurus berhasil diubah!');
    }

    public function bagan()
    {
        $page_title = 'Pengurus';
        $page_description = 'Bagan Pengurus';

        return view('data.pengurus.bagan', compact('page_title', 'page_description'));
    }

    public function ajaxBagan()
    {
        return response()->json($this->getDataStrukturOrganisasi());
    }
}
