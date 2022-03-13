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

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPesanRequest;
use App\Http\Requests\PesanRequest;
use App\Models\DataDesa;
use App\Models\Pesan;
use App\Models\PesanDetail;

class PesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(PesanRequest $request)
    {
        if ($request->has('pesan_id')) {
            // insert percakapan
            $response = PesanDetail::create($request->all());
        } else {
            $desa = DataDesa::where('desa_id', '=', $request->kode_desa)->first();
            if ($desa == null) {
                return response()->json(['status ' => false, 'message' => 'kode desa tidak terdaftar' ]);
            }
            $request['das_data_desa_id'] = $desa->id;
            $request['jenis'] = 'Pesan Masuk';
            // insert subject pesan
            $pesan = Pesan::create($request->all());
            // insert percakapan
            $response = $pesan->detailPesan()->create($request->all());
        }

        return response()->json(['result'=>$response]);
    }

    public function getpesan(GetPesanRequest $request)
    {
        $pesan = Pesan::whereHas('detailPesan', function ($q) {
            $q->where('id', '=', 2)->select('*');
        })->with(['detailPesan' => function ($query) {
            $query->select('*');
        }])->get();

        return response()->json(['result'=>$pesan]);
    }
}
