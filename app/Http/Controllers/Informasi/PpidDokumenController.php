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

namespace App\Http\Controllers\Informasi;

use App\Enums\PpidStatusEnum;
use App\Enums\PpidTipeDokumenEnum;
use App\Http\Controllers\Controller;
use App\Http\Requests\PpidDokumenRequest;
use App\Models\PpidDokumen;
use App\Models\PpidJenisDokumen;
use App\Traits\HandlesFileUpload;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class PpidDokumenController extends Controller
{
    use HandlesFileUpload;

    public function index()
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Daftar Dokumen PPID';
        $jenis_dokumen = PpidJenisDokumen::aktif()->get();

        return view('ppid.dokumen.index', compact('page_title', 'page_description', 'jenis_dokumen'));
    }

    public function getData(Request $request)
    {
        $query = PpidDokumen::with('jenisDokumen');

        // Filters
        if ($request->filled('jenis_dokumen_id')) {
            $query->where('jenis_dokumen_id', $request->jenis_dokumen_id);
        }
        if ($request->filled('status')) {
            $query->where('status', $request->status);
        }
        if ($request->filled('tipe_dokumen')) {
            $query->where('tipe_dokumen', $request->tipe_dokumen);
        }
        if ($request->filled('tanggal_mulai') && $request->filled('tanggal_selesai')) {
            $query->whereBetween('tanggal_publikasi', [$request->tanggal_mulai, $request->tanggal_selesai]);
        }

        return DataTables::of($query)
            ->addColumn('aksi', function ($row) {
                $data['show_url'] = route('ppid.dokumen.show', $row->id);
                $data['edit_url'] = route('ppid.dokumen.edit', $row->id);
                $data['delete_url'] = route('ppid.dokumen.destroy', $row->id);

                if ($row->tipe_dokumen === PpidTipeDokumenEnum::File) {
                    $data['download_url'] = route('ppid.dokumen.download', $row->id);
                }

                return view('forms.aksi', $data);
            })
            ->editColumn('status', function ($row) {
                $badgeClass = $row->status === PpidStatusEnum::Terbit ? 'label-success' : 'label-danger';

                return "<span class=\"label {$badgeClass}\">".PpidStatusEnum::options()[$row->status].'</span>';
            })
            ->editColumn('tipe_dokumen', function ($row) {
                $badgeClass = $row->tipe_dokumen === PpidTipeDokumenEnum::File ? 'label-info' : 'label-warning';

                return "<span class=\"label {$badgeClass}\">".strtoupper($row->tipe_dokumen).'</span>';
            })
            ->editColumn('jenis_dokumen', function ($row) {
                return $row->jenisDokumen->nama ?? '-';
            })
            ->editColumn('tanggal_publikasi', function ($row) {
                return $row->tanggal_publikasi ? $row->tanggal_publikasi->format('d/m/Y') : '-';
            })
            ->filterColumn('tanggal_publikasi', function ($query, $keyword) {
                $query->whereRaw("DATE_FORMAT(tanggal_publikasi, '%d/%m/%Y') LIKE ?", ["%{$keyword}%"]);
            })
            ->rawColumns(['aksi', 'status', 'tipe_dokumen'])
            ->make(true);
    }

    public function create()
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Tambah Dokumen PPID';
        $jenis_dokumen = PpidJenisDokumen::aktif()->pluck('nama', 'id');
        $tipe_dokumen_options = PpidTipeDokumenEnum::options();
        $status_options = PpidStatusEnum::options();

        return view('ppid.dokumen.create', compact(
            'page_title',
            'page_description',
            'jenis_dokumen',
            'tipe_dokumen_options',
            'status_options'
        ));
    }

    public function store(PpidDokumenRequest $request)
    {
        try {
            $input = $request->validated();

            // Handle file upload if tipe_dokumen is file
            if ($input['tipe_dokumen'] === PpidTipeDokumenEnum::File) {
                $this->handleFileUpload($request, $input, 'file_path', 'ppid/dokumen');
                $input['url'] = null;
            } else {
                $input['file_path'] = null;
            }

            PpidDokumen::create($input);

            return redirect()->route('ppid.dokumen.index')
                ->with('success', 'Dokumen PPID berhasil ditambahkan!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Dokumen PPID gagal ditambahkan!');
        }
    }

    public function show(PpidDokumen $dokumen)
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Detail Dokumen PPID';

        return view('ppid.dokumen.show', compact('page_title', 'page_description', 'dokumen'));
    }

    public function edit(PpidDokumen $dokumen)
    {
        $page_title = 'Dokumen PPID';
        $page_description = 'Ubah Dokumen PPID';
        $jenis_dokumen = PpidJenisDokumen::aktif()->pluck('nama', 'id');
        $tipe_dokumen_options = PpidTipeDokumenEnum::options();
        $status_options = PpidStatusEnum::options();

        return view('ppid.dokumen.edit', compact(
            'page_title',
            'page_description',
            'dokumen',
            'jenis_dokumen',
            'tipe_dokumen_options',
            'status_options'
        ));
    }

    public function update(PpidDokumenRequest $request, PpidDokumen $dokumen)
    {
        try {
            $input = $request->validated();

            // BUG-001: Cleanup old file when switching from FILE to URL
            if ($dokumen->tipe_dokumen === PpidTipeDokumenEnum::File && $input['tipe_dokumen'] === PpidTipeDokumenEnum::Url) {
                if (! empty($dokumen->file_path) && file_exists(public_path($dokumen->file_path))) {
                    unlink(public_path($dokumen->file_path));
                }
                $input['file_path'] = null;
            }

            // BUG-001: Cleanup old URL when switching from URL to FILE
            if ($dokumen->tipe_dokumen === PpidTipeDokumenEnum::Url && $input['tipe_dokumen'] === PpidTipeDokumenEnum::File) {
                $input['url'] = null;
            }

            // BUG-002: Handle file upload with old file cleanup
            if ($input['tipe_dokumen'] === PpidTipeDokumenEnum::File) {
                if ($request->hasFile('file_path')) {
                    // Delete old file before uploading new one
                    if (! empty($dokumen->file_path) && file_exists(public_path($dokumen->file_path))) {
                        unlink(public_path($dokumen->file_path));
                    }
                    $this->handleFileUpload($request, $input, 'file_path', 'ppid/dokumen');
                    $input['url'] = null;
                }
                // If no new file uploaded but tipe is still FILE, keep existing file
                elseif (! empty($dokumen->file_path)) {
                    $input['file_path'] = $dokumen->file_path;
                }
            }

            $dokumen->update($input);

            return redirect()->route('ppid.dokumen.index')
                ->with('success', 'Dokumen PPID berhasil diubah!');
        } catch (\Exception $e) {
            report($e);

            return back()->withInput()
                ->with('error', 'Dokumen PPID gagal diubah!');
        }
    }

    public function destroy(PpidDokumen $dokumen)
    {
        try {
            $dokumen->delete();

            return redirect()->route('ppid.dokumen.index')
                ->with('success', 'Dokumen PPID berhasil dihapus!');
        } catch (\Exception $e) {
            report($e);

            return redirect()->route('ppid.dokumen.index')
                ->with('error', 'Dokumen PPID gagal dihapus!');
        }
    }

    /**
     * Download file dokumen PPID.
     *
     * BUG-003: Validate tipe dokumen must be FILE
     * BUG-004: Path traversal protection with file exists check
     *
     * @return \Symfony\Component\HttpFoundation\BinaryFileResponse|\Illuminate\Http\RedirectResponse
     */
    public function download(PpidDokumen $dokumen)
    {
        try {
            // BUG-003: Validate tipe dokumen harus FILE
            if ($dokumen->tipe_dokumen !== PpidTipeDokumenEnum::File) {
                return back()->with('error', 'Dokumen ini bukan tipe file.');
            }

            // Validate file_path tidak kosong
            if (empty($dokumen->file_path)) {
                return back()->with('error', 'File tidak ditemukan.');
            }

            // BUG-004: Path traversal protection - validate file path
            $filePath = public_path($dokumen->file_path);

            // Validate file ada di dalam storage yang diizinkan
            $realPath = realpath($filePath);
            $storagePath = realpath(storage_path('app/public'));

            if ($realPath === false || strpos($realPath, $storagePath) !== 0) {
                report(new \Exception("PPID Dokumen path traversal attempt: {$dokumen->file_path}"));

                return back()->with('error', 'File tidak valid.');
            }

            // Validate file exists
            if (! file_exists($filePath)) {
                report(new \Exception("PPID Dokumen file not found: {$filePath}"));

                return back()->with('error', 'File tidak ditemukan atau telah dihapus.');
            }

            // Validate file readable
            if (! is_readable($filePath)) {
                report(new \Exception("PPID Dokumen file not readable: {$filePath}"));

                return back()->with('error', 'File tidak dapat dibaca.');
            }

            return response()->download($filePath);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Terjadi kesalahan saat mengunduh file.');
        }
    }
}
