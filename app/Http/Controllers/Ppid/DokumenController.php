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

use App\Models\PpidDokumen;
use App\Models\PpidJenisDokumen;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\Ppid\DokumenRequest;
use Yajra\DataTables\DataTables;

class DokumenController extends Controller
{
    use HandlesFileUpload;

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\View\View
     */
    public function index()
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Daftar Dokumen PPID';
        $jenis_dokumen = PpidJenisDokumen::aktif()->get();

        return view('ppid.dokumen.index', compact('page_title', 'page_description', 'jenis_dokumen'));
    }

    /**
     * Get data for DataTables.
     *
     * @return \Yajra\DataTables\DataTables
     */
    public function getDataDokumen()
    {
        $query = PpidDokumen::with('jenisDokumen');

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                $data['edit_url'] = route('ppid.dokumen.edit', $row->id);
                $data['delete_url'] = route('ppid.dokumen.destroy', $row->id);
                $data['download_url'] = route('ppid.dokumen.download', $row->id);
                return view('forms.aksi', $data);
            })
            ->editColumn('jenis_dokumen', function ($row) {
                return $row->jenisDokumen->nama ?? '-';
            })
            ->editColumn('status', function ($row) {
                return $row->status ? '<span class="badge badge-success">Aktif</span>' : '<span class="badge badge-danger">Tidak Aktif</span>';
            })
            ->editColumn('file', function ($row) {
                return $row->file ? '<span class="badge badge-info">Ada File</span>' : '<span class="badge badge-secondary">Tidak Ada File</span>';
            })
            ->rawColumns(['aksi', 'status', 'file'])
            ->make();
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\View\View
     */
    public function create()
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Tambah Dokumen PPID';
        $jenis_dokumen = PpidJenisDokumen::aktif()->get();

        return view('ppid.dokumen.create', compact('page_title', 'page_description', 'jenis_dokumen'));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \App\Http\Requests\Ppid\DokumenRequest  $request
     * @return \Illuminate\Http\Response
     */
    public function store(DokumenRequest $request)
    {
        try {
            $input = $request->validated();

            // Handle file upload
            $this->handleFileUpload($request, $input, 'file', 'ppid_dokumen');

            // Set status default
            if (!isset($input['status'])) {
                $input['status'] = true;
            }

            PpidDokumen::create($input);

            return redirect()->route('ppid.dokumen.index')->with('success', 'Dokumen PPID berhasil ditambahkan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Dokumen PPID gagal ditambahkan!');
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\PpidDokumen  $dokumen
     * @return \Illuminate\View\View
     */
    public function edit(PpidDokumen $dokumen)
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Ubah Dokumen PPID';
        $jenis_dokumen = PpidJenisDokumen::aktif()->get();

        return view('ppid.dokumen.edit', compact('page_title', 'page_description', 'dokumen', 'jenis_dokumen'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \App\Http\Requests\Ppid\DokumenRequest  $request
     * @param  \App\Models\PpidDokumen  $dokumen
     * @return \Illuminate\Http\Response
     */
    public function update(DokumenRequest $request, PpidDokumen $dokumen)
    {
        try {
            $input = $request->validated();

            // Handle file upload
            $this->handleFileUpload($request, $input, 'file', 'ppid_dokumen');

            // Set status default
            if (!isset($input['status'])) {
                $input['status'] = false;
            }

            $dokumen->update($input);

            return redirect()->route('ppid.dokumen.index')->with('success', 'Dokumen PPID berhasil diupdate!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()->with('error', 'Dokumen PPID gagal diupdate!');
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\PpidDokumen  $dokumen
     * @return \Illuminate\Http\RedirectResponse
     */
    public function destroy(PpidDokumen $dokumen)
    {
        try {
            $dokumen->delete();
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Dokumen PPID gagal dihapus!');
        }

        return redirect()->route('ppid.dokumen.index')->with('success', 'Dokumen PPID berhasil dihapus!');
    }

    /**
     * Download the specified document.
     *
     * @param  \App\Models\PpidDokumen  $dokumen
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse
     */
    public function download(PpidDokumen $dokumen)
    {
        try {
            $filePath = public_path('storage/ppid_dokumen/' . $dokumen->file);

            if (!file_exists($filePath)) {
                return back()->with('error', 'File tidak ditemukan!');
            }

            return response()->download($filePath, $dokumen->judul . '.' . pathinfo($filePath, PATHINFO_EXTENSION));
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'File tidak dapat diunduh!');
        }
    }
}
