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
use App\Http\Controllers\Api\Frontend\FormDokumenController;
use App\Http\Controllers\Api\Frontend\GaleriController;
use App\Http\Controllers\Api\Frontend\KategoriController;
use App\Http\Controllers\Api\Frontend\ProfilController;
use App\Http\Controllers\Api\Frontend\RegulasiController;
use App\Http\Controllers\Api\Frontend\StatistikPendudukController;
use App\Http\Controllers\Api\Frontend\KomplainController;
use App\Http\Controllers\Api\Frontend\WebsiteController;
use App\Http\Controllers\Api\Frontend\AlbumController;
use App\Http\Controllers\Api\Frontend\KesehatanController;
use App\Http\Controllers\Api\Frontend\PendidikanController;
use App\Http\Controllers\Api\Frontend\PotensiController;
use App\Http\Controllers\Api\Frontend\ProgramBantuanController;
use App\Http\Controllers\Api\Frontend\AnggaranRealisasiController;
use App\Http\Controllers\Api\Frontend\AnggaranDesaController;

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
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Kategori API Routes
     */
    Route::group(['prefix' => 'kategori', 'controller' => KategoriController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/kategori                
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    Route::group(['prefix' => 'website', 'controller' => WebsiteController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/kategori                
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Profil API Routes
     */
    Route::group(['prefix' => 'profil', 'controller' => ProfilController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/profil        
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    Route::group(['prefix' => 'desa', 'controller' => DesaController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/desa        
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    Route::group(['prefix' => 'statistik-penduduk', 'controller' => StatistikPendudukController::class], function () {
        Route::get('/', 'index');
        Route::get('/listYear', 'listYear');                                    // GET /api/v1/statistik-penduduk
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Komplain API Routes
     */
    Route::group(['prefix' => 'komplain', 'controller' => KomplainController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/komplain
        Route::post('/', 'store');                                   // POST /api/v1/komplain
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Galeri API Routes
     */
    Route::group(['prefix' => 'galeri', 'controller' => GaleriController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/galeri
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Album API Routes
     */
    Route::group(['prefix' => 'album', 'controller' => AlbumController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/album
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Potensi API Routes
     */
    Route::group(['prefix' => 'potensi', 'controller' => PotensiController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/potensi
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Form Dokumen API Routes
     */
    Route::group(['prefix' => 'form-dokumen', 'controller' => FormDokumenController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/form-dokumen
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    /**
     * Regulasi API Routes
     */
    Route::group(['prefix' => 'regulasi', 'controller' => RegulasiController::class], function () {
        Route::get('/', 'index');                                    // GET /api/v1/regulasi
        Route::delete('cache/{prefix?}', 'removeCachePrefix');
    });

    Route::group(['prefix' => 'statistik'], function () {
        Route::group(['controller' => PendidikanController::class], function () {
            Route::get('chart-tingkat-pendidikan', 'getChartTingkatPendidikan')->name('api.statistik.pendidikan.chart-tingkat-pendidikan');
            Route::get('chart-putus-sekolah', 'getChartPutusSekolah')->name('api.statistik.pendidikan.chart-putus-sekolah');
            Route::get('chart-fasilitas-paud', 'getChartFasilitasPAUD')->name('api.statistik.pendidikan.chart-fasilitas-paud');
        });

        Route::group(['controller' => KesehatanController::class], function () {
            Route::get('chart-akiakb', 'getChartAKIAKB')->name('api.statistik.kesehatan.chart-akiakb');
            Route::get('chart-imunisasi', 'getChartImunisasi')->name('api.statistik.kesehatan.chart-imunisasi');
            Route::get('chart-penyakit', 'getChartEpidemiPenyakit')->name('api.statistik.kesehatan.chart-penyakit');
            Route::get('chart-sanitasi', 'getChartToiletSanitasi')->name('api.statistik.kesehatan.chart-sanitasi');
        });
        Route::group(['controller' => ProgramBantuanController::class], function () {
            Route::get('chart-penduduk', 'getChartBantuanPenduduk')->name('api.statistik.program-bantuan.chart-penduduk');
            Route::get('chart-keluarga', 'getChartBantuanKeluarga')->name('api.statistik.program-bantuan.chart-keluarga');
        });
        Route::get('chart-anggaran-realisasi', [AnggaranRealisasiController::class,'getChartAnggaranRealisasi'])->name('api.statistik.chart-anggaran-realisasi');
        Route::get('chart-anggaran-desa', [AnggaranDesaController::class,'getChartAnggaranDesa'])->name('api.statistik.chart-anggaran-desa');
    });
});
