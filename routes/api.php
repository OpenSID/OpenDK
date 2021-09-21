<?php

/*
 * File ini bagian dari:
 *
 * PBB Desa
 *
 * Aplikasi dan source code ini dirilis berdasarkan lisensi GPL V3
 *
 * Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2016 - 2021 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

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

Route::group(['prefix' => 'v1'], function () {
    /**
     * Authentication api
     */
    Route::group(['prefix' => 'auth'], function () {
        Route::post('login', 'Api\Auth\AuthController@login');
        Route::post('logout', 'Api\Auth\AuthController@logout');
        Route::post('refresh', 'Api\Auth\AuthController@refresh');
        Route::get('me', 'Api\Auth\AuthController@me');
    });

    /**
     * Penduduk
     */
    Route::group(['prefix' => 'penduduk'], function () {
        Route::post('/', 'Api\PendudukController@store');
        Route::post('storedata', 'Api\PendudukController@storedata');
        Route::post('test', 'Api\PendudukController@test');
    });

    /**
     * Laporan Apbdes
     */
    Route::group(['prefix' => 'laporan-apbdes'], function () {
        Route::post('/', 'Api\LaporanApbdesController@store');
    });

    /**
     * Laporan Penduduk
     */
    Route::group(['prefix' => 'laporan-penduduk'], function () {
        Route::post('/', 'Api\LaporanPendudukController@store');
    });
});
