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

use App\Http\Controllers\Api\Auth\AuthController;
use App\Http\Controllers\Api\LaporanApbdesController;
use App\Http\Controllers\Api\LaporanPendudukController;
use App\Http\Controllers\Api\PembangunanController;
use App\Http\Controllers\Api\PendudukController;
use App\Http\Controllers\Api\PesanController;
use App\Http\Controllers\Api\ProfilDesaController;
use App\Http\Controllers\Api\ProgamBantuanController;
use App\Http\Controllers\Api\SuratController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register API routes for your application. These
| routes are loaded by the RouteServiceProvider within a group which
| is assigned the "api" middleware group. Enjoy building your API!
|
*/

// Route::middleware('auth:api')->get('/user', function (Request $request) {
//     return $request->user();
// });

Route::group(['prefix' => 'v1', 'middleware' => 'xss_sanitization'], function () {
    /**
     * Authentication api
     */
    Route::group(['prefix' => 'auth', 'controller' => AuthController::class], function () {
        Route::post('login', 'login');
        Route::post('logout', 'logout');
        Route::post('refresh', 'refresh');
        Route::get('me', 'me');
    });

    Route::group(['middleware' => ['auth:api', 'token.registered']], function () {
        // digunakan untuk test apakah api berjalan
        Route::get('test', function () {
            return response()->json('Welcome to api route');
        });
        /**
         * Penduduk
         */
        Route::group(['prefix' => 'penduduk', 'controller' => PendudukController::class], function () {
            Route::post('/', 'store');
            Route::post('storedata', 'storedata');
            Route::post('test', 'test');
        });

        /**
         * Laporan Apbdes
         */
        Route::group(['prefix' => 'laporan-apbdes', 'controller' => LaporanApbdesController::class], function () {
            Route::post('/', 'store');
        });

        /**
         * Laporan Penduduk
         */
        Route::group(['prefix' => 'laporan-penduduk', 'controller' => LaporanPendudukController::class], function () {
            Route::post('/', 'store');
        });

        Route::group(['prefix' => 'pesan', 'controller' => PesanController::class], function () {
            Route::post('/', 'store');
            Route::post('getpesan', 'getPesan');
            Route::get('detail', 'detail');
        });

        /**
         * Pembangunan
         */
        Route::group(['prefix' => 'pembangunan', 'controller' => PembangunanController::class], function () {
            Route::post('/', 'store');
            Route::post('dokumentasi', 'storeDokumentasi');
        });

        /**
         * Identitas Desa
         */
        Route::group(['prefix' => 'identitas-desa', 'controller' => ProfilDesaController::class], function () {
            Route::post('/', 'store');
        });

        /**
         * Program Bantuan
         */
        Route::group(['prefix' => 'program-bantuan', 'controller' => ProgamBantuanController::class], function () {
            Route::post('/', 'store');
            Route::post('peserta', 'storePeserta');
        });

        //Surat
        Route::group(['prefix' => 'surat', 'controller' => SuratController::class], function () {
            Route::get('/', 'index');
            Route::post('kirim', 'store');
            Route::get('download', 'download');
        });
    });
});
