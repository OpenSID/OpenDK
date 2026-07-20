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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\LaporanApbdesRequest;
use App\Jobs\LaporanApbdesQueueJob;

class LaporanApbdesController extends Controller
{
    /**
     * Sinkronisasi data APBDes dari OpenSID.
     *
     * @group OpenSID Integration
     *
     * @bodyParam desa_id string required Kode desa. Example: 3201012001
     * @bodyParam laporan_apbdes array required Data laporan APBDes.
     * @response {
     *   "status": "success",
     *   "message": "Proses sync data Laporan Apbdes OpenSID sedang berjalan"
     * }
     */
    public function store(LaporanApbdesRequest $request)
    {
        // dispatch queue job apbdes
        LaporanApbdesQueueJob::dispatch($request->only(['desa_id', 'laporan_apbdes']));

        return response()->json([
            'status' => 'success',
            'message' => 'Proses sync data APBDes OpenSID sedang berjalan',
        ]);
    }
}
