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
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class PesanController extends Controller
{
    public function __construct()
    {
        $this->middleware('auth:api');
    }

    public function store(PesanRequest $request)
    {
        $desa = DataDesa::where('desa_id', '=', $request->kode_desa)->first();
        if ($desa == null) {
            return response()->json(['status' => false, 'message' => 'Desa tidak terdaftar' ]);
        }

        if ($request->has('pesan_id')) {
            // insert percakapan
            try {
                $response = PesanDetail::create([
                    'pesan_id' => $request->pesan_id,
                    'text' => $request->text,
                    'pesan' => $desa->id,
                ]);
                return response()->json(['status' => true, 'message' => 'Berhasil mengirim pesan' ]);
            } catch (Exception $e) {
                return response()->json(['status' => false, 'message' => 'error Exception' ]);
            }
        } else {
            try {
                DB::transaction(function () use ($request, $desa) {
                    $id = Pesan::insertGetId([
                        'das_data_desa_id' => $desa->id,
                        'judul' => $request->get('judul'),
                        'jenis' => 'Pesan Masuk',
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);

                    $id_detail = PesanDetail::insertGetId([
                        'pesan_id' => $id,
                        'text' => Purify::clean($request->get('pesan')),
                        'desa_id' =>  $desa->id,
                        'created_at' => Carbon::now(),
                        'updated_at' => Carbon::now()
                    ]);
                });
                return response()->json(['status' => true, 'message' => 'Berhasil mengirim pesan' ]);
            } catch (Exception $e) {
                return response()->json(['status' => false, 'message' => 'error Exception' ]);
            }
        }

        return response()->json(['result'=>$response]);
    }

    public function getpesan(GetPesanRequest $request)
    {
        // cek desa
        $desa = DataDesa::where('desa_id', '=', $request->kode_desa)->first();

        if ($desa == null) {
            return response()->json(['status' => false, 'message' => 'Desa tidak terdaftar' ]);
        }

        $pesan = Pesan::whereHas('detailPesan', function ($q) use ($request, $desa) {
            // ambil id lebih
            // $q->where('id', '=', $desa->id)->select('*');
        })->with(['detailPesan' => function ($query) {
            $query->select('*');
        }])->where('das_data_desa_id', '=', $desa->id)->get();

        return response()->json(['status' => true, 'data'=>$pesan]);
    }

    public function detail(Request $request)
    {
        if ($request->has('id')) {
            $pesan_id = (int) $request->id;
            $pesan = Pesan::with(['detailPesan' => function ($query) {
                $query->select('*');
            }])
            ->where('id', '=', $pesan_id)
            ->first();

            return response()->json(['status' => true, 'data'=>$pesan]);
        }
        return response()->json(['status' => true, 'message' => 'Tidak ada Pesan untuk ditampilkan' ]);
    }
}
