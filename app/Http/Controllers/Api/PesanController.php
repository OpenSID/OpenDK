<?php

/*
 * File ini bagian dari:
 *
 * OpenDK
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @copyright  Hak Cipta 2017 - 2025 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link       https://github.com/OpenSID/opendk
 */

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Http\Requests\GetPesanRequest;
use App\Http\Requests\PesanRequest;
use App\Models\Pesan;
use App\Models\PesanDetail;
use App\Services\DesaService;
use Exception;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Stevebauman\Purify\Facades\Purify;

class PesanController extends Controller
{
    /**
     * Kirim pesan baru atau balas pesan dari OpenSID.
     *
     * @group OpenSID Integration
     *
     * @bodyParam kode_desa string required Kode desa pengirim. Example: 3201012001
     * @bodyParam judul string Judul pesan (wajib untuk pesan baru). Example: Laporan Bulanan
     * @bodyParam pesan string required Isi pesan. Example: Berikut kami kirimkan laporan bulanan.
     * @bodyParam pengirim string required Pengirim pesan. Example: operator@desa.id
     * @bodyParam nama_pengirim string required Nama pengirim. Example: Ahmad
     * @bodyParam pesan_id int ID pesan untuk membalas percakapan yang sudah ada. Example: 5
     *
     * @response {
     *   "status": true,
     *   "message": "Berhasil mengirim pesan"
     * }
     */
    public function store(PesanRequest $request)
    {
        $desa = (new DesaService())->getDesaByKode($request->kode_desa);
        if ($desa == null) {
            return response()->json(['status' => false, 'message' => 'Desa tidak terdaftar']);
        }

        if ($request->has('pesan_id')) {
            // insert percakapan
            try {
                PesanDetail::create([
                    'pesan_id' => (int) $request->pesan_id,
                    'text' => $request->pesan,
                    'pengirim' => $request->pengirim,
                    'nama_pengirim' => $request->nama_pengirim,
                ]);
                Pesan::where('id', (int) $request->pesan_id)->update(['sudah_dibaca' => 0]);

                return response()->json(['status' => true, 'message' => 'Berhasil mengirim pesan']);
            } catch (Exception $e) {
                return response()->json(['status' => false, 'message' => 'error Exception']);
            }
        } else {
            try {
                DB::transaction(function () use ($request, $desa) {
                    $id = Pesan::create([
                        'das_data_desa_id' => $desa->desa_id,
                        'judul' => $request->get('judul'),
                        'jenis' => Pesan::PESAN_MASUK,
                        'additional_info' => ['nama_desa' => $desa->nama],
                    ])->id;

                    PesanDetail::create([
                        'pesan_id' => $id,
                        'text' => Purify::clean($request->get('pesan')),
                        'pengirim' => $request->pengirim,
                        'nama_pengirim' => $request->nama_pengirim,
                    ]);
                });

                return response()->json(['status' => true, 'message' => 'Berhasil mengirim pesan']);
            } catch (Exception $e) {
                return response()->json(['status' => false, 'message' => 'error Exception']);
            }
        }

        return response()->json(['result' => 'unknow method']);
    }

    /**
     * Ambil daftar pesan untuk desa tertentu.
     *
     * @group OpenSID Integration
     *
     * @bodyParam kode_desa string required Kode desa. Example: 3201012001
     * @bodyParam id int ID pesan terakhir yang diterima (untuk pagination). Example: 0
     *
     * @response {
     *   "status": true,
     *   "data": [{"id": 1, "judul": "Laporan", "detailPesan": []}]
     * }
     */
    public function getpesan(GetPesanRequest $request)
    {
        // cek desa
        $desa = (new DesaService())->getDesaByKode($request->kode_desa);

        if ($desa == null) {
            return response()->json(['status' => false, 'message' => 'Desa tidak terdaftar']);
        }

        $pesan = Pesan::whereHas('detailPesan', function ($q) use ($request) {
            $q->where('id', '>', $request->id);
        })
        ->with(['detailPesan' => function ($q) use ($request) {
            $q->where('id', '>', $request->id);
        }])
        ->where('das_data_desa_id', $desa->desa_id)
        ->orWhere('diarsipkan', 1)
        ->get();

        return response()->json(['status' => true, 'data' => $pesan]);
    }

    /**
     * Lihat detail percakapan pesan.
     *
     * @group OpenSID Integration
     *
     * @queryParam id int required ID pesan. Example: 1
     *
     * @response {
     *   "status": true,
     *   "data": {"id": 1, "judul": "Laporan", "detailPesan": [{"id": 1, "text": "Isi pesan"}]}
     * }
     */
    public function detail(Request $request)
    {
        if ($request->has('id')) {
            $pesan_id = (int) $request->id;
            $pesan = Pesan::with(['detailPesan'])
            ->where('id', '=', $pesan_id)
            ->first();

            return response()->json(['status' => true, 'data' => $pesan]);
        }

        return response()->json(['status' => true, 'message' => 'Tidak ada Pesan untuk ditampilkan']);
    }

    public function setArsipPesan(Request $request)
    {
        $array = json_decode($request->get('array_id'));
        $pesan = Pesan::whereIn('id', $array)->update([
            'diarsipkan' => Pesan::MASUK_ARSIP,
        ]);

        if ($pesan > 0) {
            return back()->with('success', 'Pesan berhasil ditandai!');
        }

        return back()->withInput()->with('error', 'Pesan gagal diarsipkan!');
    }
}
