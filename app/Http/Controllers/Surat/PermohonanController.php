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

use App\Enums\LogVerifikasiSurat;
use App\Http\Controllers\Controller;
use App\Models\Surat;
use Yajra\DataTables\DataTables;

class PermohonanController extends Controller
{
    public function index()
    {
        $page_title       = 'Permohonan Surat';
        $page_description = 'Daftar Permohonan Surat';
        $surat            = Surat::permohonan()->get();

        return view('surat.permohonan.index', compact('page_title', 'page_description', 'surat'));
    }

    public function getData()
    {
        return DataTables::of(Surat::permohonan())
            ->addColumn('aksi', function ($row) {
                $data['download_url']   = route('surat.permohonan.download', $row->id);

                return view('forms.aksi', $data);
            })
            ->editColumn('log_verifikasi', function ($row) {
                if ($row->log_verifikasi == LogVerifikasiSurat::Camat) {
                    return 'Camat';
                } else if ($row->log_verifikasi == LogVerifikasiSurat::Sekretaris) {
                    return 'Sekretaris';
                } else {
                    return 'Operator';
                }
            })
            ->rawColumns(['aksi', 'log_verifikasi'])->make();
    }

    public function download($id)
    {
        dd('unduh');
    }
}