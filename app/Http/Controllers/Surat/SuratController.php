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

namespace App\Http\Controllers\Surat;

use App\Enums\StatusSurat;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengaturanSuratRequest;
use App\Models\Profil;
use App\Models\SettingAplikasi;
use App\Models\Surat;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;
use Illuminate\View\View;
use Symfony\Component\HttpFoundation\BinaryFileResponse;
use Yajra\DataTables\DataTables;

class SuratController extends Controller
{
    public function arsip(): View
    {
        $page_title = 'Arsip Surat';
        $page_description = 'Daftar Arsip Surat';

        return view('surat.arsip', compact('page_title', 'page_description'));
    }

    public function getData(): JsonResponse
    {
        $desa = request()->get('kode_desa');
        return DataTables::of(Surat::arsip()
            ->when($desa, function ($query) use ($desa) {
                if ($desa !== 'Semua') {
                    $desa = preg_replace('/\D/', '', $desa);
                }
                return $desa === 'Semua'
                    ? $query
                    : $query->whereRaw("REPLACE(desa_id, '.', '') = ?", [$desa]);
            }))
            ->addColumn('aksi', function ($row) {
                $data['download_url'] = auth()->user()->can('access.surat.arsip.export') ? route('surat.arsip.download', $row->id) : null;

                $pathSurat = asset('storage/surat/' . $row->file);
                $data['preview_url'] = $pathSurat;
                return view('forms.aksi', $data);
            })
            ->editColumn('tanggal', function ($row) {
                return format_date($row->tanggal);
            })
            ->editColumn('nama_penduduk', function ($row) {
                if (isset($row->penduduk) && !empty($row->penduduk->nama)) {
                    return $row->penduduk->nama;
                }
                return $row->nama_penduduk;
            })
            ->addColumn('hash', function ($row) {
                if ($row->file_hash) {
                    return '<code style="font-size: 10px;">' . e(substr($row->file_hash, 0, 16)) . '...</code>';
                }
                return '-';
            })
            ->rawColumns(['aksi', 'hash'])->make();
    }

    public function download(Surat $surat): BinaryFileResponse
    {
        try {
            return Storage::download('public/surat/' . $surat->file);
        } catch (\Exception $e) {
            Log::error('Surat download failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
                'surat_id' => $surat->id,
            ]);

            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }

    public function pengaturan(): View
    {
        $formAction = route('surat.pengaturan.update');
        $camat = $this->akun_camat;
        $sekretaris = $this->akun_sekretaris;
        $profil = Profil::first();
        $page_title = 'Pengaturan Surat';
        $page_description = 'Daftar Pengaturan Surat';

        return view('surat.pengaturan', compact('page_title', 'page_description', 'formAction', 'camat', 'sekretaris'));
    }

    public function pengaturan_update(PengaturanSuratRequest $request): RedirectResponse
    {
        try {
            foreach ($request->all() as $key => $value) {
                SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
            }
        } catch (\Exception $e) {
            Log::error('Pengaturan Surat update failed', [
                'error' => $e->getMessage(),
                'user_id' => auth()->id(),
            ]);

            return back()->withInput()->with('error', 'Pengaturan Surat gagal diubah!');
        }

        return redirect()->route('surat.pengaturan')->with('success', 'Pengaturan Surat berhasil diubah!');
    }

    public function qrcode(Surat $surat): View
    {
        abort_if($surat->status !== StatusSurat::Arsip, 404);
        $profil = Profil::first();

        return view('surat.qrcode', compact('surat', 'profil'));
    }

    public function verifikasi(): View
    {
        $page_title = 'Verifikasi Surat';
        $page_description = 'Verifikasi keaslian surat digital';

        return view('surat.verifikasi.index', compact('page_title', 'page_description'));
    }

    public function verifikasiStore(Request $request): View|RedirectResponse
    {
        $request->validate([
            'file' => 'required|mimes:pdf|max:5120',
        ]);

        try {
            $uploadedFile = $request->file('file');
            $uploadedHash = hash_file('sha256', $uploadedFile->getRealPath());

            $surat = Surat::where('file_hash', $uploadedHash)->where('status', StatusSurat::Arsip)->first();

            if (!$surat) {
                return back()->with('error', 'Surat tidak ditemukan atau file tidak sesuai dengan surat yang diterbitkan.');
            }

            return view('surat.verifikasi.hasil', compact('surat'));
        } catch (\Exception $e) {
            Log::error('Verifikasi surat failed', [
                'error' => $e->getMessage(),
            ]);

            return back()->with('error', 'Terjadi kesalahan saat memverifikasi surat.');
        }
    }
}
