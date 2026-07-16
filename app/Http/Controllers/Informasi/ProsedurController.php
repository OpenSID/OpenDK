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

use App\Models\Prosedur;
use Yajra\DataTables\DataTables;
use App\Traits\HandlesFileUpload;
use App\Http\Controllers\Controller;
use App\Http\Requests\ProsedurRequest;
use Illuminate\Support\Facades\Log;

class ProsedurController extends Controller
{
    use HandlesFileUpload;

    public function index()
    {
        $page_title = 'Prosedur';
        $page_description = 'Daftar Prosedur';

        return view('informasi.prosedur.index', compact('page_title', 'page_description'));
    }

    public function getDataProsedur()
    {
        return DataTables::of(Prosedur::select('id', 'judul_prosedur', 'file_prosedur', 'mime_type'))
            ->addIndexColumn()
            ->addColumn('jenis_file', function ($row) {
                if ($row->file_prosedur) {
                    $ext = strtolower(pathinfo($row->file_prosedur, PATHINFO_EXTENSION));
                    return strtoupper($ext);
                }
                return '-';
            })
            ->addColumn('ukuran_file', function ($row) {
                if ($row->file_prosedur) {
                    $path = $row->file_prosedur;
                    $filePath = null;

                    if (file_exists(public_path($path))) {
                        $filePath = public_path($path);
                    } elseif (file_exists(base_path('public/' . $path))) {
                        $filePath = base_path('public/' . $path);
                    } else {
                        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $path));
                        if (file_exists($storagePath)) {
                            $filePath = $storagePath;
                        }
                    }

                    if ($filePath && file_exists($filePath)) {
                        $bytes = filesize($filePath);
                        $units = ['B', 'KB', 'MB', 'GB', 'TB'];
                        $bytes = max($bytes, 0);
                        $pow = floor(($bytes ? log($bytes) : 0) / log(1024));
                        $pow = min($pow, count($units) - 1);
                        $bytes /= pow(1024, $pow);
                        return round($bytes, 2) . ' ' . $units[$pow];
                    }
                }
                return '-';
            })
            ->addColumn('aksi', function ($row) {
                if (!auth()->guest()) {
                    $data['edit_url'] = auth()->user()->can('access.informasi.prosedur.edit') ? route('informasi.prosedur.edit', $row->id) : null;
                    $data['delete_url'] = auth()->user()->can('access.informasi.prosedur.delete') ? route('informasi.prosedur.destroy', $row->id) : null;
                }

                $data['download_url'] = auth()->user()->can('access.informasi.prosedur.export') ? route('informasi.prosedur.download', $row->id) : null;
                $data['preview_url'] = route('informasi.prosedur.preview', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('judul_prosedur', function ($row) {
                return $row->judul_prosedur;
            })->make();
    }

    public function create()
    {
        $page_title = 'Prosedur';
        $page_description = 'Tambah Prosedur';

        return view('informasi.prosedur.create', compact('page_title', 'page_description'));
    }

    public function store(ProsedurRequest $request)
    {
        try {
            $input = $request->all();
            $input['slug'] = str_slug($request->input('judul_prosedur'));
            $this->handleFileUpload($request, $input, 'file_prosedur', 'regulasi');

            $input['mime_type'] = $request->file('file_prosedur')->getClientMimeType();
            Prosedur::create($input);
        } catch (\Exception $e) {
            Log::error('Prosedur creation failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->with('error', 'Prosedur gagal disimpan!');
        }

        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur berhasil disimpan!');
    }

    public function show(Prosedur $prosedur)
    {
        $page_title = 'Prosedur';
        $page_description = 'Detail Prosedur : ' . $prosedur->judul_prosedur;

        return view('informasi.prosedur.show', compact('page_title', 'page_description', 'prosedur'));
    }

    public function edit(Prosedur $prosedur)
    {
        $page_title = 'Prosedur';
        $page_description = 'Ubah Prosedur : ' . $prosedur->judul_prosedur;

        return view('informasi.prosedur.edit', compact('page_title', 'page_description', 'prosedur'));
    }

    public function update(Prosedur $prosedur, ProsedurRequest $request)
    {
        try {
            $input = $request->all();
            $this->handleFileUpload($request, $input, 'file_prosedur', 'regulasi');

            if ($request->hasFile('file_prosedur')) {
                $input['mime_type'] = $request->file('file_prosedur')->getClientMimeType();
            }

            $prosedur->update($input);
        } catch (\Exception $e) {
            Log::error('Prosedur update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'prosedur_id' => $prosedur->id,
            ]);

            return back()->with('error', 'Prosedur gagal disimpan!');
        }

        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur berhasil disimpan!');
    }

    public function destroy(Prosedur $prosedur)
    {
        try {
            $prosedur->delete();
        } catch (\Exception $e) {
            Log::error('Prosedur deletion failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'prosedur_id' => $prosedur->id,
            ]);

            return back()->withInput()->with('error', 'Prosedur gagal dihapus!');
        }

        return redirect()->route('informasi.prosedur.index')->with('success', 'Prosedur berhasil disimpan!');
    }

    public function download(Prosedur $prosedur)
    {
        try {
            return response()->download($prosedur->file_prosedur);
        } catch (\Exception $e) {
            Log::error('Prosedur download failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'prosedur_id' => $prosedur->id,
            ]);

            return back()->with('error', 'Dokumen prosedur tidak ditemukan');
        }
    }

    public function preview(Prosedur $prosedur)
    {
        $path = $prosedur->file_prosedur;

        if (file_exists(public_path($path))) {
            return response()->file(public_path($path));
        }

        if (file_exists(base_path('public/' . $path))) {
            return response()->file(base_path('public/' . $path));
        }

        $storagePath = storage_path('app/public/' . str_replace('storage/', '', $path));
        if (file_exists($storagePath)) {
            return response()->file($storagePath);
        }

        abort(404, 'File tidak ditemukan.');
    }
}
