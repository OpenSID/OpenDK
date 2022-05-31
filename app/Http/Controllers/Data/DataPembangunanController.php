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

namespace App\Http\Controllers\Data;

use App\Http\Controllers\Controller;
use App\Models\DataDesa;
use App\Models\Pembangunan;
use App\Models\PembangunanDokumentasi;
use Illuminate\Http\Request;
use Yajra\DataTables\DataTables;

class DataPembangunanController extends Controller
{
    public function index()
    {
        $page_title       = 'Pembangunan';
        $page_description = 'Data Pembangunan';
        $list_desa        = DataDesa::get();

        return view('data.pembangunan.index', compact('page_title', 'page_description', 'list_desa'));
    }

    public function getPembangunan(Request $request)
    {
        if (request()->ajax()) {
            $desa = $request->input('desa');

            $pembangunan = Pembangunan::when($desa, function ($q) use ($desa) {
                return $desa === 'Semua'
                ? $q : $q->where('desa_id', $desa);
            })
            ->with('dokumentasi');

            return DataTables::of($pembangunan)
                ->addColumn('aksi', function ($row) {
                    $data['detail_url']   = route('data.pembangunan.rincian', ['id' => $row->id,'desa_id' => $row->desa_id]);
                    return view('forms.aksi', $data);
                })->make();
        }
    }

    public function rincian($id, $desa_id)
    {
        $page_title       = 'Pembangunan';
        $page_description = 'Rincian Pembangunan';
        $pembangunan =  Pembangunan::where('id', $id)->where('desa_id', $desa_id)->first();

        return view('data.pembangunan.rincian', compact('page_title', 'page_description', 'pembangunan'));
    }

    public function getrinciandata($id, $desa_id)
    {
        if (request()->ajax()) {
            $pembangunanDokumentasi = PembangunanDokumentasi::where('desa_id', $desa_id)->where('id_pembangunan', $id)->get();
            return DataTables::of($pembangunanDokumentasi)
            ->addIndexColumn()
            ->make();
        }
    }
}
