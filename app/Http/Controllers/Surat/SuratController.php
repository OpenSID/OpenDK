<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Surat;

use App\Models\Surat;
use Illuminate\Http\Request;
use App\Models\SettingAplikasi;
use Yajra\DataTables\DataTables;
use App\Http\Controllers\Controller;
use App\Http\Requests\PengaturanSuratRequest;

class SuratController extends Controller
{
    public function arsip()
    {
        $page_title       = 'Arsip Surat';
        $page_description = 'Daftar Arsip Surat';
        $surat            = Surat::arsip()->get();

        return view('surat.arsip', compact('page_title', 'page_description', 'surat'));
    }

    public function getData()
    {
        return DataTables::of(Surat::permohonan())
            ->addColumn('aksi', function ($row) {
                $data['download_url']   = route('surat.permohonan.download', $row->id);

                return view('forms.aksi', $data);
            })
            ->rawColumns(['aksi', 'log_verifikasi'])->make();
    }

    public function download($id)
    {
        dd('unduh');
    }
    
    public function pengaturan()
    {
        $settings         = SettingAplikasi::where('kategori', 'surat')->pluck('value', 'key');
        $formAction       = route('surat.pengaturan.update');
        $page_title       = 'Pegaturan Surat';
        $page_description = 'Daftar Pegaturan Surat';

        return view('surat.pengaturan', compact('page_title', 'page_description', 'settings', 'formAction'));
    }

    public function pengaturan_update(PengaturanSuratRequest $request)
    {
        try {
            foreach ($request->all() as $key => $value) {
                SettingAplikasi::where('key', '=', $key)->update(['value' => $value]);
            }
        } catch (\Exception $e) {
            report($e);
            return back()->withInput()->with('error', 'Pengaturan Surat gagal diubah!');
        }

        return redirect()->route('surat.pengaturan')->with('success', 'Pengaturan Surat berhasil diubah!');
    }
}
