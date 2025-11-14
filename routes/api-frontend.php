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

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Api\Frontend\ArtikelController;
use App\Http\Controllers\Api\Frontend\DesaController;
use App\Http\Controllers\Api\Frontend\ProfilController;
use App\Http\Controllers\Api\Frontend\StatistikPendudukController;
use App\Http\Controllers\Api\Frontend\KomplainController;

/*
|--------------------------------------------------------------------------
| Frontend API Routes
|--------------------------------------------------------------------------
|
| Here is where you can register frontend API routes for your application.
| These routes are typically accessible without authentication and are
| designed for public consumption by frontend applications.
|
*/

Route::group(['prefix' => 'v1', 'middleware' => ['xss_sanitization']], function () {
    
    /**
     * Artikel API Routes
     */
    Route::group(['prefix' => 'artikel', 'controller' => ArtikelController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/artikel        
        Route::post('/{id}/comments', 'storeComment');              // POST /api/v1/artikel/{id}/comments
        Route::delete('cache/{prefix?}','removeCachePrefix');
    });

    /**
     * Profil API Routes
     */
    Route::group(['prefix' => 'profil', 'controller' => ProfilController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/profil        
        Route::delete('cache/{prefix?}','removeCachePrefix');
    });

    Route::group(['prefix' => 'desa', 'controller' => DesaController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/desa        
        Route::delete('cache/{prefix?}','removeCachePrefix');
    });

    Route::group(['prefix' => 'statistik-penduduk', 'controller' => StatistikPendudukController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/statistik-penduduk
        Route::delete('cache/{prefix?}','removeCachePrefix');
    });

    /**
     * Komplain API Routes
     */
    Route::group(['prefix' => 'komplain', 'controller' => KomplainController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/komplain
        Route::post('/', 'store');                                   // POST /api/v1/komplain
        Route::get('/{id}', 'show');                                 // GET /api/v1/komplain/{id}
        Route::post('/{id}/reply', 'reply');                          // POST /api/v1/komplain/{id}/reply
        Route::get('/{id}/replies', 'getReplies');                   // GET /api/v1/komplain/{id}/replies
        Route::get('/track/{tracking_id}', 'track');                  // GET /api/v1/komplain/track/{tracking_id}
        Route::delete('cache/{prefix?}','removeCachePrefix');
    });
});