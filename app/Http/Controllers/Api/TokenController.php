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
use Illuminate\Http\Response;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Log;
use Tymon\JWTAuth\Facades\JWTAuth;

class TokenController extends Controller
{
    /**
     * Generate token sinkronisasi dengan masa berlaku 1 tahun.
     *
     * Token ini digunakan untuk keperluan sinkronisasi data antar sistem,
     * bukan untuk sesi autentikasi umum.
     *
     * @return Response
     */
    public function index()
    {
        $user = Auth::user();

        // Gunakan customClaims untuk set expiry 1 tahun secara terisolasi,
        // tanpa mengubah konfigurasi JWT global via Config::set().
        // Ini memastikan TTL default untuk token lain tidak terpengaruh.
        $expiresAt = now()->addYear()->timestamp;

        $token = JWTAuth::customClaims(['exp' => $expiresAt])
            ->fromUser($user);

        Log::info('Token sinkronisasi digenerate', [
            'user_id'    => $user->id,
            'expires_at' => now()->addYear()->toDateTimeString(),
            'ip'         => request()->ip(),
        ]);

        return response()->json([
            'token'      => $token,
            'expires_at' => now()->addYear()->toDateTimeString(),
            'token_type' => 'Bearer',
        ]);
    }
}
