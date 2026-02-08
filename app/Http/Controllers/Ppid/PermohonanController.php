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

namespace App\Http\Controllers\Ppid;

use App\Models\PpidPermohonan;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ppid\PermohonanRequest;
use Yajra\DataTables\DataTables;
use Illuminate\Http\Request;

class PermohonanController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = 'Permohonan Informasi PPID';
        $page_description = 'Daftar Permohonan Informasi PPID';

        return view('ppid.permohonan.index', compact('page_title', 'page_description'));
    }

    /**
     * Get data for DataTables.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Yajra\DataTables\DataTables
     */
    public function getDataPermohonan(Request $request)
    {
        $query = PpidPermohonan::query();

        // Filter by status
        if ($request->has('status') && $request->status != '') {
            $query->where('status', $request->status);
        }

        return DataTables::of($query->latest())
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('ppid.permohonan.show', $row->id);
                $data['edit_url'] = route('ppid.permohonan.edit', $row->id);
                $data['delete_url'] = route('ppid.permohonan.destroy', $row->id);
                return view('forms.aksi', $data);
            })
            ->editColumn('status', function ($row) {
                $badgeClass = match($row->status) {
                    'MENUNGGU' => 'badge-warning',
                    'DIPROSES' => 'badge-info',
                    'SELESAI' => 'badge-success',
                    'DITOLAK' => 'badge-danger',
                    default => 'badge-secondary'
                };
                return '<span class="badge ' . $badgeClass . '">' . $row->status . '</span>';
            })
            ->editColumn('cara_memperoleh', function ($row) {
                return $row->cara_memperoleh == 'ONLINE' ? 'Online' : 'Offline';
            })
            ->editColumn('created_at', function ($row) {
                return $row->created_at->format('d/m/Y H:i');
            })
            ->filterColumn('status', function ($query, $keyword) {
                $query->where('status', 'like', '%' . $keyword . '%');
            })
            ->rawColumns(['aksi', 'status'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Permohonan Informasi PPID';
        $page_description = 'Tambah Permohonan Informasi PPID';

        return view('ppid.permohonan.create', compact('page_title', 'page_description'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Ppid\PermohonanRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(PermohonanRequest $request)
    {
        try {
            $input = $request->validated();

            // Set default status
            if (!isset($input['status'])) {
                $input['status'] = 'MENUNGGU';
            }

            PpidPermohonan::create($input);

            return redirect()->route('ppid.permohonan.index')->with('success', 'Permohonan berhasil ditambahkan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Permohonan gagal ditambahkan!');
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\View\View
     */
    public function show(PpidPermohonan $permohonan)
    {
        $page_title = 'Detail Permohonan';
        $page_description = 'Detail Permohonan Informasi PPID';

        return view('ppid.permohonan.show', compact('page_title', 'page_description', 'permohonan'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\View\View
     */
    public function edit(PpidPermohonan $permohonan)
    {
        $page_title = 'Permohonan Informasi PPID';
        $page_description = 'Ubah Permohonan Informasi PPID';

        return view('ppid.permohonan.edit', compact('page_title', 'page_description', 'permohonan'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Ppid\PermohonanRequest  $request
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function update(PermohonanRequest $request, PpidPermohonan $permohonan)
    {
        try {
            $permohonan->update($request->validated());

            return redirect()->route('ppid.permohonan.index')->with('success', 'Permohonan berhasil diupdate!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Permohonan gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PpidPermohonan $permohonan)
    {
        try {
            $permohonan->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Permohonan gagal dihapus!');
        }

        return redirect()->route('ppid.permohonan.index')->with('success', 'Permohonan berhasil dihapus!');
    }

    /**
     * Proses permohonan.
     *
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function proses(PpidPermohonan $permohonan)
    {
        try {
            $permohonan->update([
                'status' => 'DIPROSES',
                'tanggal_proses' => now(),
            ]);

            return back()->with('success', 'Permohonan berhasil diproses!');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Permohonan gagal diproses!');
        }
    }

    /**
     * Selesaikan permohonan.
     *
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function selesaikan(PpidPermohonan $permohonan)
    {
        try {
            $permohonan->update([
                'status' => 'SELESAI',
                'tanggal_proses' => now(),
            ]);

            return back()->with('success', 'Permohonan berhasil diselesaikan!');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Permohonan gagal diselesaikan!');
        }
    }

    /**
     * Tolak permohonan.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\PpidPermohonan  $permohonan
     * @return \Illuminate\Http\Response
     */
    public function tolak(Request $request, PpidPermohonan $permohonan)
    {
        try {
            $request->validate([
                'keterangan' => 'required|string',
            ]);

            $permohonan->update([
                'status' => 'DITOLAK',
                'keterangan' => $request->keterangan,
                'tanggal_proses' => now(),
            ]);

            return back()->with('success', 'Permohonan berhasil ditolak!');
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Permohonan gagal ditolak!');
        }
    }
}
