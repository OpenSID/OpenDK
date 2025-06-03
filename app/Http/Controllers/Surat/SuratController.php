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
use Illuminate\Support\Facades\Storage;
use Yajra\DataTables\DataTables;

class SuratController extends Controller
{
    public function arsip()
    {
        $page_title = 'Arsip Surat';
        $page_description = 'Daftar Arsip Surat';

        return view('surat.arsip', compact('page_title', 'page_description'));
    }

    public function getData()
    {
        return DataTables::of(Surat::arsip())
            ->addColumn('aksi', function ($row) {
                $data['download_url'] = route('surat.arsip.download', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('tanggal', function ($row) {
                return format_date($row->tanggal);
            })
            ->rawColumns(['aksi'])->make();
    }

    public function download($id)
    {
        try {
            $surat = Surat::findOrFail($id);

            return Storage::download('public/surat/'.$surat->file);
        } catch (\Exception $e) {
            report($e);

            return back()->with('error', 'Dokumen tidak ditemukan');
        }
    }

    public function pengaturan()
    {
        $formAction = route('surat.pengaturan.update');
        $camat = $this->akun_camat;
        $sekretaris = $this->akun_sekretaris;
        $profil = Profil::first();
        $page_title = 'Pengaturan Surat';
        $page_description = 'Daftar Pengaturan Surat';

        return view('surat.pengaturan', compact('page_title', 'page_description', 'formAction', 'camat', 'sekretaris'));
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

    public function qrcode($id)
    {
        $surat = Surat::where('id', '=', $id)->where('status', '=', StatusSurat::Arsip)->firstOrFail();
        $profil = Profil::first();

        return view('surat.qrcode', compact('surat', 'profil'));
    }
}
