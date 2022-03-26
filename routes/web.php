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

use App\Models\DataDesa;
use App\Models\Penduduk;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cookie;
use Illuminate\Support\Facades\Route;

// Redirect if apps not installed
Route::group(['middleware' => 'installed'], function () {
    Auth::routes([
        'register' => false,
    ]);

    /**
     * Group Routing for Halaman Website
     */
    Route::namespace('Page')->group(function () {
        Route::get('/', 'PageController@index')->name('beranda');
        Route::get('berita-desa', 'PageController@beritaDesa')->name('berita-desa');
        Route::get('filter-berita-desa', 'PageController@filterFeeds')->name('filter-berita-desa');

        Route::group(['prefix' => 'berita'], function () {
            Route::permanentRedirect('/', '/');
            Route::get('{slug}', 'PageController@detailBerita')->name('berita.detail');
        });

        Route::group(['prefix' => 'profil'], function () {
            Route::get('letak-geografis', 'ProfilController@LetakGeografis')->name('profil.letak-geografis');
            Route::get('struktur-pemerintahan', 'ProfilController@StrukturPemerintahan')->name('profil.struktur-pemerintahan');
            Route::get('visi-dan-misi', 'ProfilController@VisiMisi')->name('profil.visi-misi');
            Route::get('sejarah', 'ProfilController@sejarah')->name('profil.sejarah');
        });

        Route::group(['prefix' => 'event'], function () {
            Route::permanentRedirect('/', '/');
            Route::get('{slug}', 'PageController@eventDetail')->name('event.detail');
        });

        Route::group(['prefix' => 'desa'], function () {
            Route::permanentRedirect('/', '/');
            Route::get('desa-{slug}', 'PageController@DesaShow')->name('desa.show');
        });

        Route::group(['prefix' => 'potensi'], function () {
            Route::permanentRedirect('/', '/');
            Route::get('{slug}', 'PageController@PotensiByKategory')->name('potensi.kategori');
            Route::get('{kategori}/{slug}', 'PageController@PotensiShow')->name('potensi.kategori.show');
        });

        Route::any('refresh-captcha', 'PageController@refresh_captcha')->name('refresh-captcha');

        Route::group(['prefix' => 'statistik'], function () {
            Route::get('kependudukan', 'KependudukanController@showKependudukan')->name('statistik.kependudukan');
            Route::get('show-kependudukan', 'KependudukanController@showKependudukanPartial')->name('statistik.show-kependudukan');
            Route::get('chart-kependudukan', 'KependudukanController@getChartPenduduk')->name('statistik.chart-kependudukan');
            Route::get('chart-kependudukan-usia', 'KependudukanController@getChartPendudukUsia')->name('statistik.chart-kependudukan-usia');
            Route::get('chart-kependudukan-pendidikan', 'KependudukanController@getChartPendudukPendidikan')->name('statistik.chart-kependudukan-pendidikan');
            Route::get('chart-kependudukan-goldarah', 'KependudukanController@getChartPendudukGolDarah')->name('statistik.chart-kependudukan-goldarah');
            Route::get('chart-kependudukan-kawin', 'KependudukanController@getChartPendudukKawin')->name('statistik.chart-kependudukan-kawin');
            Route::get('chart-kependudukan-agama', 'KependudukanController@getChartPendudukAgama')->name('statistik.chart-kependudukan-agama');
            Route::get('chart-kependudukan-kelamin', 'KependudukanController@getChartPendudukKelamin')->name('statistik.chart-kependudukan-kelamin');
            Route::get('data-penduduk', 'KependudukanController@getDataPenduduk')->name('statistik.data-penduduk');

            Route::get('pendidikan', 'PendidikanController@showPendidikan')->name('statistik.pendidikan');
            Route::get('chart-tingkat-pendidikan', 'PendidikanController@getChartTingkatPendidikan')->name('statistik.pendidikan.chart-tingkat-pendidikan');
            Route::get('chart-putus-sekolah', 'PendidikanController@getChartPutusSekolah')->name('statistik.pendidikan.chart-putus-sekolah');
            Route::get('chart-fasilitas-paud', 'PendidikanController@getChartFasilitasPAUD')->name('statistik.pendidikan.chart-fasilitas-paud');

            Route::get('program-dan-bantuan', 'ProgramBantuanController@showProgramBantuan')->name('statistik.program-bantuan');
            Route::get('chart-penduduk', 'ProgramBantuanController@getChartBantuanPenduduk')->name('statistik.program-bantuan.chart-penduduk');
            Route::get('chart-keluarga', 'ProgramBantuanController@getChartBantuanKeluarga')->name('statistik.program-bantuan.chart-keluarga');

            Route::get('anggaran-dan-realisasi', 'AnggaranRealisasiController@showAnggaranDanRealisasi')->name('statistik.anggaran-dan-realisasi');
            Route::get('chart-anggaran-realisasi', 'AnggaranRealisasiController@getChartAnggaranRealisasi')->name('statistik.chart-anggaran-realisasi');

            Route::get('anggaran-desa', 'AnggaranDesaController@showAnggaranDesa')->name('statistik.anggaran-desa');
            Route::get('chart-anggaran-desa', 'AnggaranDesaController@getChartAnggaranDesa')->name('statistik.chart-anggaran-desa');

            Route::get('kesehatan', 'KesehatanController@showKesehatan')->name('statistik.kesehatan');
            Route::get('chart-akiakb', 'KesehatanController@getChartAKIAKB')->name('statistik.kesehatan.chart-akiakb');
            Route::get('chart-imunisasi', 'KesehatanController@getChartImunisasi')->name('statistik.kesehatan.chart-imunisasi');
            Route::get('chart-penyakit', 'KesehatanController@getChartEpidemiPenyakit')->name('statistik.kesehatan.chart-penyakit');
            Route::get('chart-sanitasi', 'KesehatanController@getChartToiletSanitasi')->name('statistik.kesehatan.chart-sanitasi');
        });

        Route::group(['prefix' => 'unduhan'], function () {
            Route::permanentRedirect('/', '/');

            Route::group(['prefix' => 'prosedur'], function () {
                Route::permanentRedirect('/', '/');
                Route::get('/', 'DownloadController@indexProsedur')->name('unduhan.prosedur');
                Route::get('getdata', 'DownloadController@getDataProsedur')->name('unduhan.prosedur.getdata');
                Route::get('{nama_prosedur}', 'DownloadController@showProsedur')->name('unduhan.prosedur.show');
                Route::get('{file}/download', 'DownloadController@downloadProsedur')->name('unduhan.prosedur.download');
            });

            Route::group(['prefix' => 'regulasi'], function () {
                Route::permanentRedirect('/', '/');
                Route::get('/', 'DownloadController@indexRegulasi')->name('unduhan.regulasi');
                Route::get('{nama_regulasi}', 'DownloadController@showRegulasi')->name('unduhan.regulasi.show');
                Route::get('{file}/download', 'DownloadController@downloadRegulasi')->name('unduhan.regulasi.download');
            });

            Route::group(['prefix' => 'form-dokumen'], function () {
                Route::permanentRedirect('/', '/');
                Route::get('/', 'DownloadController@indexFormDokumen')->name('unduhan.form-dokumen');
                Route::get('getdata', 'DownloadController@getDataDokumen')->name('unduhan.form-dokumen.getdata');
            });
        });

        Route::get('faq', 'WebFaqController@index')->name('faq');
    });
    Route::get('agenda-kegiatan/{slug}', 'Informasi\EventController@show')->name('event.show');

    Route::namespace('SistemKomplain')->group(function () {
        Route::group(['prefix' => 'sistem-komplain'], function () {
            Route::get('/', ['as' => 'sistem-komplain.index', 'uses' => 'SistemKomplainController@index']);
            Route::get('kirim', ['as' => 'sistem-komplain.kirim', 'uses' => 'SistemKomplainController@kirim']);
            Route::post('store', ['as' => 'sistem-komplain.store', 'uses' => 'SistemKomplainController@store']);
            Route::get('edit/{id}', ['as' => 'sistem-komplain.edit', 'uses' => 'SistemKomplainController@edit']);
            Route::put('update/{id}', ['as' => 'sistem-komplain.update', 'uses' => 'SistemKomplainController@update']);
            Route::delete('destroy/{id}', ['as' => 'sistem-komplain.destroy', 'uses' => 'SistemKomplainController@destroy']);
            Route::get('komplain/{slug}', ['as' => 'sistem-komplain.komplain', 'uses' => 'SistemKomplainController@show']);
            Route::get('komplain/kategori/{slug}', ['as' => 'sistem-komplain.kategori', 'uses' => 'SistemKomplainController@indexKategori']);
            Route::get('komplain-sukses', ['as' => 'sistem-komplain.komplain-sukses', 'uses' => 'SistemKomplainController@indexSukses']);
            Route::post('tracking', ['as' => 'sistem-komplain.tracking', 'uses' => 'SistemKomplainController@tracking']);
            Route::post('reply/{id}', ['as' => 'sistem-komplain.reply', 'uses' => 'SistemKomplainController@reply']);
            Route::get('jawabans', ['as' => 'sistem-komplain.jawabans', 'uses' => 'SistemKomplainController@getJawabans']);
        });
    });

    /**
     * Group Routing for Halaman Dahsboard
     */
    Route::group(['middleware' => 'auth:web'], function () {
        // Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);

        Route::get('/dashboard', 'DashboardController')->name('dashboard');

        /**
         * Group Routing for Informasi
         */
        Route::namespace('Informasi')->group(function () {
            Route::group(['prefix' => 'informasi', 'middleware' => ['role:administrator-website|super-admin|admin-kecamatan']], function () {

                // Prosedur
                Route::group(['prefix' => 'prosedur'], function () {
                    Route::get('/', ['as' => 'informasi.prosedur.index', 'uses' => 'ProsedurController@index']);
                    Route::get('show/{prosedur}', ['as' => 'informasi.prosedur.show', 'uses' => 'ProsedurController@show']);
                    Route::get('getdata', ['as' => 'informasi.prosedur.getdata', 'uses' => 'ProsedurController@getDataProsedur']);
                    Route::get('create', ['as' => 'informasi.prosedur.create', 'uses' => 'ProsedurController@create']);
                    Route::post('store', ['as' => 'informasi.prosedur.store', 'uses' => 'ProsedurController@store']);
                    Route::get('edit/{prosedur}', ['as' => 'informasi.prosedur.edit', 'uses' => 'ProsedurController@edit']);
                    Route::put('update/{prosedur}', ['as' => 'informasi.prosedur.update', 'uses' => 'ProsedurController@update']);
                    Route::delete('destroy/{prosedur}', ['as' => 'informasi.prosedur.destroy', 'uses' => 'ProsedurController@destroy']);
                    Route::get('download/{prosedur}', ['as' => 'informasi.prosedur.download', 'uses' => 'ProsedurController@download']);
                });

                // Regulasi
                Route::group(['prefix' => 'regulasi'], function () {
                    Route::get('/', ['as' => 'informasi.regulasi.index', 'uses' => 'RegulasiController@index']);
                    Route::get('show/{regulasi}', ['as' => 'informasi.regulasi.show', 'uses' => 'RegulasiController@show']);
                    Route::get('create', ['as' => 'informasi.regulasi.create', 'uses' => 'RegulasiController@create']);
                    Route::post('store', ['as' => 'informasi.regulasi.store', 'uses' => 'RegulasiController@store']);
                    Route::get('edit/{regulasi}', ['as' => 'informasi.regulasi.edit', 'uses' => 'RegulasiController@edit']);
                    Route::put('update/{regulasi}', ['as' => 'informasi.regulasi.update', 'uses' => 'RegulasiController@update']);
                    Route::delete('destroy/{regulasi}', ['as' => 'informasi.regulasi.destroy', 'uses' => 'RegulasiController@destroy']);
                    Route::get('download/{regulasi}', ['as' => 'informasi.regulasi.download', 'uses' => 'RegulasiController@download']);
                });

                // FAQ
                Route::group(['prefix' => 'faq'], function () {
                    Route::get('/', ['as' => 'informasi.faq.index', 'uses' => 'FaqController@index']);
                    Route::get('show/{id}', ['as' => 'informasi.faq.show', 'uses' => 'FaqController@show']);
                    Route::get('create', ['as' => 'informasi.faq.create', 'uses' => 'FaqController@create']);
                    Route::post('store', ['as' => 'informasi.faq.store', 'uses' => 'FaqController@store']);
                    Route::get('edit/{id}', ['as' => 'informasi.faq.edit', 'uses' => 'FaqController@edit']);
                    Route::post('update/{id}', ['as' => 'informasi.faq.update', 'uses' => 'FaqController@update']);
                    Route::delete('destroy/{id}', ['as' => 'informasi.faq.destroy', 'uses' => 'FaqController@destroy']);
                });

                // Events
                Route::group(['prefix' => 'event'], function () {
                    Route::get('/', ['as' => 'informasi.event.index', 'uses' => 'EventController@index']);
                    Route::get('show/{event}', ['as' => 'informasi.event.show', 'uses' => 'EventController@show']);
                    Route::get('create', ['as' => 'informasi.event.create', 'uses' => 'EventController@create']);
                    Route::post('store', ['as' => 'informasi.event.store', 'uses' => 'EventController@store']);
                    Route::get('edit/{event}', ['as' => 'informasi.event.edit', 'uses' => 'EventController@edit']);
                    Route::post('update/{event}', ['as' => 'informasi.event.update', 'uses' => 'EventController@update']);
                    Route::delete('destroy/{event}', ['as' => 'informasi.event.destroy', 'uses' => 'EventController@destroy']);
                });

                // Artikel
                Route::group(['prefix' => 'artikel'], function () {
                    '\vendor\UniSharp\LaravelFilemanager\Lfm::routes()';
                    Route::get('/', ['as' => 'informasi.artikel.index', 'uses' => 'ArtikelController@index']);
                    Route::get('create', ['as' => 'informasi.artikel.create', 'uses' => 'ArtikelController@create']);
                    Route::post('store', ['as' => 'informasi.artikel.store', 'uses' => 'ArtikelController@store']);
                    Route::get('edit/{artikel}', ['as' => 'informasi.artikel.edit', 'uses' => 'ArtikelController@edit']);
                    Route::post('update/{artikel}', ['as' => 'informasi.artikel.update', 'uses' => 'ArtikelController@update']);
                    Route::delete('destroy/{artikel}', ['as' => 'informasi.artikel.destroy', 'uses' => 'ArtikelController@destroy']);
                    Route::get('getdata', ['as' => 'informasi.artikel.getdata', 'uses' => 'ArtikelController@getDataArtikel']);
                });

                // Form Dokumen
                Route::group(['prefix' => 'form-dokumen'], function () {
                    Route::get('/', ['as' => 'informasi.form-dokumen.index', 'uses' => 'FormDokumenController@index']);
                    Route::get('show/{dokumen}', ['as' => 'informasi.form-dokumen.show', 'uses' => 'FormDokumenController@show']);
                    Route::get('create', ['as' => 'informasi.form-dokumen.create', 'uses' => 'FormDokumenController@create']);
                    Route::get('getdata', ['as' => 'informasi.form-dokumen.getdata', 'uses' => 'FormDokumenController@getDataDokumen']);
                    Route::post('store', ['as' => 'informasi.form-dokumen.store', 'uses' => 'FormDokumenController@store']);
                    Route::get('edit/{dokumen}', ['as' => 'informasi.form-dokumen.edit', 'uses' => 'FormDokumenController@edit']);
                    Route::put('update/{dokumen}', ['as' => 'informasi.form-dokumen.update', 'uses' => 'FormDokumenController@update']);
                    Route::delete('destroy/{dokumen}', ['as' => 'informasi.form-dokumen.destroy', 'uses' => 'FormDokumenController@destroy']);
                    Route::get('download/{dokumen}', ['as' => 'informasi.form-dokumen.download', 'uses' => 'FormDokumenController@download']);
                });

                // Potensi
                Route::group(['prefix' => 'potensi'], function () {
                    Route::get('/', ['as' => 'informasi.potensi.index', 'uses' => 'PotensiController@index']);
                    Route::get('show/{potensi}', ['as' => 'informasi.potensi.show', 'uses' => 'PotensiController@show']);
                    Route::get('create', ['as' => 'informasi.potensi.create', 'uses' => 'PotensiController@create']);
                    Route::post('store', ['as' => 'informasi.potensi.store', 'uses' => 'PotensiController@store']);
                    Route::get('edit/{potensi}', ['as' => 'informasi.potensi.edit', 'uses' => 'PotensiController@edit']);
                    Route::put('update/{potensi}', ['as' => 'informasi.potensi.update', 'uses' => 'PotensiController@update']);
                    Route::delete('destroy/{potensi}', ['as' => 'informasi.potensi.destroy', 'uses' => 'PotensiController@destroy']);
                    Route::get('getdata', ['as' => 'informasi.potensi.getdata', 'uses' => 'PotensiController@getDataPotensi']);
                    Route::get('kategori', ['as' => 'informasi.potensi.kategori', 'uses' => 'PotensiController@kategori']);
                });
            });
        });

        /**
         * Group Routing for Data
         */
        Route::namespace('Data')->group(function () {
            Route::group(['prefix' => 'data'], function () {

                // Profil
                Route::group(['prefix' => 'profil'], function () {
                    Route::get('/', ['as' => 'data.profil.index', 'uses' => 'ProfilController@index']);
                    Route::put('update/{id}', ['as' => 'data.profil.update', 'uses' => 'ProfilController@update']);
                    Route::get('success/{id}', ['as' => 'data.profil.success', 'uses' => 'ProfilController@success']);
                });

                // Data Umum
                Route::group(['prefix' => 'data-umum', 'middleware' => ['role:super-admin|data-kecamatan']], function () {
                    Route::get('/', ['as' => 'data.data-umum.index', 'uses' => 'DataUmumController@index']);
                    Route::get('getdataajax', ['as' => 'data.data-umum.getdataajax', 'uses' => 'DataUmumController@getDataUmumAjax']);
                    Route::put('update/{id}', ['as' => 'data.data-umum.update', 'uses' => 'DataUmumController@update']);
                });

                // Data Desa
                Route::group(['prefix' => 'data-desa', 'middleware' => ['role:super-admin|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.data-desa.index', 'uses' => 'DataDesaController@index']);
                    Route::get('getdata', ['as' => 'data.data-desa.getdata', 'uses' => 'DataDesaController@getDataDesa']);
                    Route::post('getdesa', ['as' => 'data.data-desa.getdesa', 'uses' => 'DataDesaController@getDesaKecamatan']);
                    Route::get('create', ['as' => 'data.data-desa.create', 'uses' => 'DataDesaController@create']);
                    Route::post('store', ['as' => 'data.data-desa.store', 'uses' => 'DataDesaController@store']);
                    Route::get('edit/{id}', ['as' => 'data.data-desa.edit', 'uses' => 'DataDesaController@edit']);
                    Route::put('update/{id}', ['as' => 'data.data-desa.update', 'uses' => 'DataDesaController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.data-desa.destroy', 'uses' => 'DataDesaController@destroy']);
                });

                // Penduduk
                Route::group(['prefix' => 'penduduk', 'middleware' => ['role:super-admin|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.penduduk.index', 'uses' => 'PendudukController@index']);
                    Route::get('getdata', ['as' => 'data.penduduk.getdata', 'uses' => 'PendudukController@getPenduduk']);
                    Route::get('show/{id}', ['as' => 'data.penduduk.show', 'uses' => 'PendudukController@show']);
                    Route::get('import', ['as' => 'data.penduduk.import', 'uses' => 'PendudukController@import']);
                    Route::post('import-excel', ['as' => 'data.penduduk.import-excel', 'uses' => 'PendudukController@importExcel']);
                });

                // Keluarga
                Route::group(['prefix' => 'keluarga', 'middleware' => ['role:super-admin|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.keluarga.index', 'uses' => 'KeluargaController@index']);
                    Route::get('getdata', ['as' => 'data.keluarga.getdata', 'uses' => 'KeluargaController@getKeluarga']);
                    Route::get('show/{id}', ['as' => 'data.keluarga.show', 'uses' => 'KeluargaController@show']);
                });

                // Laporan Penduduk
                Route::group(['prefix' => 'laporan-penduduk', 'middleware' => ['role:super-admin|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.laporan-penduduk.index', 'uses' => 'LaporanPendudukController@index']);
                    Route::get('getdata', ['as' => 'data.laporan-penduduk.getdata', 'uses' => 'LaporanPendudukController@getData']);
                    Route::delete('destroy/{id}', ['as' => 'data.laporan-penduduk.destroy', 'uses' => 'LaporanPendudukController@destroy']);
                    Route::get('download{id}', ['as' => 'data.laporan-penduduk.download', 'uses' => 'LaporanPendudukController@download']);
                    Route::get('import', ['as' => 'data.laporan-penduduk.import', 'uses' => 'LaporanPendudukController@import']);
                    Route::post('do_import', ['as' => 'data.laporan-penduduk.do_import', 'uses' => 'LaporanPendudukController@do_import']);
                });

                // AKI & AKB
                Route::group(['prefix' => 'aki-akb', 'middleware' => ['role:super-admin|admin-puskesmas']], function () {
                    Route::get('/', ['as' => 'data.aki-akb.index', 'uses' => 'AKIAKBController@index']);
                    Route::get('getdata', ['as' => 'data.aki-akb.getdata', 'uses' => 'AKIAKBController@getDataAKIAKB']);
                    Route::get('edit/{id}', ['as' => 'data.aki-akb.edit', 'uses' => 'AKIAKBController@edit']);
                    Route::put('update/{id}', ['as' => 'data.aki-akb.update', 'uses' => 'AKIAKBController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.aki-akb.destroy', 'uses' => 'AKIAKBController@destroy']);
                    Route::get('import', ['as' => 'data.aki-akb.import', 'uses' => 'AKIAKBController@import']);
                    Route::post('do_import', ['as' => 'data.aki-akb.do_import', 'uses' => 'AKIAKBController@do_import']);
                });

                // AKI & AKB
                Route::group(['prefix' => 'imunisasi', 'middleware' => ['role:super-admin|admin-puskesmas']], function () {
                    Route::get('/', ['as' => 'data.imunisasi.index', 'uses' => 'ImunisasiController@index']);
                    Route::get('getdata', ['as' => 'data.imunisasi.getdata', 'uses' => 'ImunisasiController@getDataAKIAKB']);
                    Route::get('edit/{id}', ['as' => 'data.imunisasi.edit', 'uses' => 'ImunisasiController@edit']);
                    Route::put('update/{id}', ['as' => 'data.imunisasi.update', 'uses' => 'ImunisasiController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.imunisasi.destroy', 'uses' => 'ImunisasiController@destroy']);
                    Route::get('import', ['as' => 'data.imunisasi.import', 'uses' => 'ImunisasiController@import']);
                    Route::post('do_import', ['as' => 'data.imunisasi.do_import', 'uses' => 'ImunisasiController@do_import']);
                });

                // Epidemi Penyakit
                Route::group(['prefix' => 'epidemi-penyakit', 'middleware' => ['role:super-admin|admin-puskesmas']], function () {
                    Route::get('/', ['as' => 'data.epidemi-penyakit.index', 'uses' => 'EpidemiPenyakitController@index']);
                    Route::get('getdata', ['as' => 'data.epidemi-penyakit.getdata', 'uses' => 'EpidemiPenyakitController@getDataAKIAKB']);
                    Route::get('edit/{id}', ['as' => 'data.epidemi-penyakit.edit', 'uses' => 'EpidemiPenyakitController@edit']);
                    Route::put('update/{id}', ['as' => 'data.epidemi-penyakit.update', 'uses' => 'EpidemiPenyakitController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.epidemi-penyakit.destroy', 'uses' => 'EpidemiPenyakitController@destroy']);
                    Route::get('import', ['as' => 'data.epidemi-penyakit.import', 'uses' => 'EpidemiPenyakitController@import']);
                    Route::post('do_import', ['as' => 'data.epidemi-penyakit.do_import', 'uses' => 'EpidemiPenyakitController@do_import']);
                });

                // Toilet Sanitasi
                Route::group(['prefix' => 'toilet-sanitasi', 'middleware' => ['role:super-admin|admin-puskesmas']], function () {
                    Route::get('/', ['as' => 'data.toilet-sanitasi.index', 'uses' => 'ToiletSanitasiController@index']);
                    Route::get('getdata', ['as' => 'data.toilet-sanitasi.getdata', 'uses' => 'ToiletSanitasiController@getDataAKIAKB']);
                    Route::get('edit/{id}', ['as' => 'data.toilet-sanitasi.edit', 'uses' => 'ToiletSanitasiController@edit']);
                    Route::put('update/{id}', ['as' => 'data.toilet-sanitasi.update', 'uses' => 'ToiletSanitasiController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.toilet-sanitasi.destroy', 'uses' => 'ToiletSanitasiController@destroy']);
                    Route::get('import', ['as' => 'data.toilet-sanitasi.import', 'uses' => 'ToiletSanitasiController@import']);
                    Route::post('do_import', ['as' => 'data.toilet-sanitasi.do_import', 'uses' => 'ToiletSanitasiController@do_import']);
                });

                // Tingkaat Pendidikan
                Route::group(['prefix' => 'tingkat-pendidikan', 'middleware' => ['role:super-admin|admin-pendidikan|administrator-website']], function () {
                    Route::get('/', ['as' => 'data.tingkat-pendidikan.index', 'uses' => 'TingkatPendidikanController@index']);
                    Route::get('getdata', ['as' => 'data.tingkat-pendidikan.getdata', 'uses' => 'TingkatPendidikanController@getData']);
                    Route::delete('destroy/{id}', ['as' => 'data.tingkat-pendidikan.destroy', 'uses' => 'TingkatPendidikanController@destroy']);
                    Route::get('import', ['as' => 'data.tingkat-pendidikan.import', 'uses' => 'TingkatPendidikanController@import']);
                    Route::post('do_import', ['as' => 'data.tingkat-pendidikan.do_import', 'uses' => 'TingkatPendidikanController@do_import']);
                });

                // Putus Sekolah
                Route::group(['prefix' => 'putus-sekolah', 'middleware' => ['role:super-admin|admin-pendidikan|administrator-website']], function () {
                    Route::get('/', ['as' => 'data.putus-sekolah.index', 'uses' => 'PutusSekolahController@index']);
                    Route::get('getdata', ['as' => 'data.putus-sekolah.getdata', 'uses' => 'PutusSekolahController@getDataPutusSekolah']);
                    Route::get('edit/{id}', ['as' => 'data.putus-sekolah.edit', 'uses' => 'PutusSekolahController@edit']);
                    Route::put('update/{id}', ['as' => 'data.putus-sekolah.update', 'uses' => 'PutusSekolahController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.putus-sekolah.destroy', 'uses' => 'PutusSekolahController@destroy']);
                    Route::get('import', ['as' => 'data.putus-sekolah.import', 'uses' => 'PutusSekolahController@import']);
                    Route::post('do_import', ['as' => 'data.putus-sekolah.do_import', 'uses' => 'PutusSekolahController@do_import']);
                });

                // Fasilitas PAUD
                Route::group(['prefix' => 'fasilitas-paud', 'middleware' => ['role:super-admin|admin-pendidikan|administrator-website']], function () {
                    Route::get('/', ['as' => 'data.fasilitas-paud.index', 'uses' => 'FasilitasPaudController@index']);
                    Route::get('getdata', ['as' => 'data.fasilitas-paud.getdata', 'uses' => 'FasilitasPaudController@getDataFasilitasPAUD']);
                    Route::get('edit/{id}', ['as' => 'data.fasilitas-paud.edit', 'uses' => 'FasilitasPaudController@edit']);
                    Route::put('update/{id}', ['as' => 'data.fasilitas-paud.update', 'uses' => 'FasilitasPaudController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.fasilitas-paud.destroy', 'uses' => 'FasilitasPaudController@destroy']);
                    Route::get('import', ['as' => 'data.fasilitas-paud.import', 'uses' => 'FasilitasPaudController@import']);
                    Route::post('do_import', ['as' => 'data.fasilitas-paud.do_import', 'uses' => 'FasilitasPaudController@do_import']);
                });

                // Program Bantuan
                Route::group(['prefix' => 'program-bantuan', 'middleware' => ['role:super-admin|administrator-website|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.program-bantuan.index', 'uses' => 'ProgramBantuanController@index']);
                    Route::get('getdata', ['as' => 'data.program-bantuan.getdata', 'uses' => 'ProgramBantuanController@getaProgramBantuan']);
                    Route::get('create', ['as' => 'data.program-bantuan.create', 'uses' => 'ProgramBantuanController@create']);
                    Route::post('store', ['as' => 'data.program-bantuan.store', 'uses' => 'ProgramBantuanController@store']);
                    Route::post('add_peserta', ['as' => 'data.program-bantuan.add_peserta', 'uses' => 'ProgramBantuanController@add_peserta']);
                    Route::get('edit/{id}', ['as' => 'data.program-bantuan.edit', 'uses' => 'ProgramBantuanController@edit']);
                    Route::get('show/{id}', ['as' => 'data.program-bantuan.show', 'uses' => 'ProgramBantuanController@show']);
                    Route::get('create-peserta/{id}', ['as' => 'data.program-bantuan.create-peserta', 'uses' => 'ProgramBantuanController@createPeserta']);
                    Route::put('update/{id}', ['as' => 'data.program-bantuan.update', 'uses' => 'ProgramBantuanController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.program-bantuan.destroy', 'uses' => 'ProgramBantuanController@destroy']);
                    Route::get('import', ['as' => 'data.program-bantuan.import', 'uses' => 'ProgramBantuanController@import']);
                    Route::post('do_import', ['as' => 'data.program-bantuan.do_import', 'uses' => 'ProgramBantuanController@do_import']);
                });

                // Anggaran Realisasi
                Route::group(['prefix' => 'anggaran-realisasi', 'middleware' => ['role:super-admin|administrator-website|admin-kecamatan']], function () {
                    Route::get('/', ['as' => 'data.anggaran-realisasi.index', 'uses' => 'AnggaranRealisasiController@index']);
                    Route::get('getdata', ['as' => 'data.anggaran-realisasi.getdata', 'uses' => 'AnggaranRealisasiController@getDataAnggaran']);
                    Route::get('edit/{id}', ['as' => 'data.anggaran-realisasi.edit', 'uses' => 'AnggaranRealisasiController@edit']);
                    Route::put('update/{id}', ['as' => 'data.anggaran-realisasi.update', 'uses' => 'AnggaranRealisasiController@update']);
                    Route::delete('destroy/{id}', ['as' => 'data.anggaran-realisasi.destroy', 'uses' => 'AnggaranRealisasiController@destroy']);
                    Route::get('import', ['as' => 'data.anggaran-realisasi.import', 'uses' => 'AnggaranRealisasiController@import']);
                    Route::post('do_import', ['as' => 'data.anggaran-realisasi.do_import', 'uses' => 'AnggaranRealisasiController@do_import']);
                });

                // Anggaran Desa
                Route::group(['prefix' => 'anggaran-desa', 'middleware' => ['role:super-admin|administrator-website|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.anggaran-desa.index', 'uses' => 'AnggaranDesaController@index']);
                    Route::get('getdata', ['as' => 'data.anggaran-desa.getdata', 'uses' => 'AnggaranDesaController@getDataAnggaran']);
                    Route::delete('destroy/{id}', ['as' => 'data.anggaran-desa.destroy', 'uses' => 'AnggaranDesaController@destroy']);
                    Route::get('import', ['as' => 'data.anggaran-desa.import', 'uses' => 'AnggaranDesaController@import']);
                    Route::post('do_import', ['as' => 'data.anggaran-desa.do_import', 'uses' => 'AnggaranDesaController@do_import']);
                });

                // Laporan Apbdes
                Route::group(['prefix' => 'laporan-apbdes', 'middleware' => ['role:super-admin|administrator-website|admin-desa']], function () {
                    Route::get('/', ['as' => 'data.laporan-apbdes.index', 'uses' => 'LaporanApbdesController@index']);
                    Route::get('getdata', ['as' => 'data.laporan-apbdes.getdata', 'uses' => 'LaporanApbdesController@getApbdes']);
                    Route::delete('destroy/{id}', ['as' => 'data.laporan-apbdes.destroy', 'uses' => 'LaporanApbdesController@destroy']);
                    Route::get('download{id}', ['as' => 'data.laporan-apbdes.download', 'uses' => 'LaporanApbdesController@download']);
                    Route::get('import', ['as' => 'data.laporan-apbdes.import', 'uses' => 'LaporanApbdesController@import']);
                    Route::post('do_import', ['as' => 'data.laporan-apbdes.do_import', 'uses' => 'LaporanApbdesController@do_import']);
                    Route::get('download/{id}', ['as' => 'data.laporan-apbdes.download', 'uses' => 'LaporanApbdesController@download']);
                });
            });

            // Admin SIKEMA
            Route::group(['prefix' => 'admin-komplain', 'middleware' => ['role:administrator-website|admin-komplain|super-admin']], function () {
                Route::get('/', ['as' => 'admin-komplain.index', 'uses' => 'AdminKomplainController@index']);
                Route::get('getdata', ['as' => 'admin-komplain.getdata', 'uses' => 'AdminKomplainController@getDataKomplain']);
                Route::get('edit/{id}', ['as' => 'admin-komplain.edit', 'uses' => 'AdminKomplainController@edit']);
                Route::put('update/{id}', ['as' => 'admin-komplain.update', 'uses' => 'AdminKomplainController@update']);
                Route::delete('destroy/{id}', ['as' => 'admin-komplain.destroy', 'uses' => 'AdminKomplainController@destroy']);
                Route::put('setuju/{id}', ['as' => 'admin-komplain.setuju', 'uses' => 'AdminKomplainController@disetujui']);
                Route::get('statistik', ['as' => 'admin-komplain.statistik', 'uses' => 'AdminKomplainController@statistik']);
            });
        });

        /**
         * Group Routing for Setting
         */
        Route::group(['prefix' => 'setting'], function () {

            // User Management
            Route::group(['prefix' => 'user','middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.user.index', 'uses' => 'User\UserController@index']);
                Route::get('getdata', ['as' => 'setting.user.getdata', 'uses' => 'User\UserController@getDataUser']);
                Route::get('create', ['as' => 'setting.user.create', 'uses' => 'User\UserController@create']);
                Route::post('store', ['as' => 'setting.user.store', 'uses' => 'User\UserController@store']);
                Route::get('edit/{id}', ['as' => 'setting.user.edit', 'uses' => 'User\UserController@edit']);
                Route::put('update/{id}', ['as' => 'setting.user.update', 'uses' => 'User\UserController@update']);
                Route::put('updatePassword/{id}', ['as' => 'setting.user.updatePassword', 'uses' => 'User\UserController@updatePassword']);
                Route::put('password/{id}', ['as' => 'setting.user.password', 'uses' => 'User\UserController@password']);
                Route::delete('destroy/{id}', ['as' => 'setting.user.destroy', 'uses' => 'User\UserController@destroy']);
                Route::post('active/{id}', ['as' => 'setting.user.active', 'uses' => 'User\UserController@active']);
                Route::get('photo-profil/{id}', ['as' => 'setting.user.photo', 'uses' => 'User\UserController@photo']);
                Route::put('update-photo/{id}', ['as' => 'setting.user.uphoto', 'uses' => 'User\UserController@updatePhoto']);
            });

            // Role Management
            Route::group(['prefix' => 'role', 'middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.role.index', 'uses' => 'Role\RoleController@index']);
                Route::get('getdata', ['as' => 'setting.role.getdata', 'uses' => 'Role\RoleController@getData']);
                Route::get('create', ['as' => 'setting.role.create', 'uses' => 'Role\RoleController@create']);
                Route::post('store', ['as' => 'setting.role.store', 'uses' => 'Role\RoleController@store']);
                Route::get('edit/{id}', ['as' => 'setting.role.edit', 'uses' => 'Role\RoleController@edit']);
                Route::put('update/{id}', ['as' => 'setting.role.update', 'uses' => 'Role\RoleController@update']);
                Route::delete('destroy/{id}', ['as' => 'setting.role.destroy', 'uses' => 'Role\RoleController@destroy']);
            });

            // Komplain Kategori
            Route::group(['prefix' => 'komplain-kategori', 'middleware' => ['role:super-admin|admin-komplain|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.komplain-kategori.index', 'uses' => 'Setting\KategoriKomplainController@index']);
                Route::get('getdata', ['as' => 'setting.komplain-kategori.getdata', 'uses' => 'Setting\KategoriKomplainController@getData']);
                Route::get('create', ['as' => 'setting.komplain-kategori.create', 'uses' => 'Setting\KategoriKomplainController@create']);
                Route::post('store', ['as' => 'setting.komplain-kategori.store', 'uses' => 'Setting\KategoriKomplainController@store']);
                Route::get('edit/{id}', ['as' => 'setting.komplain-kategori.edit', 'uses' => 'Setting\KategoriKomplainController@edit']);
                Route::put('update/{id}', ['as' => 'setting.komplain-kategori.update', 'uses' => 'Setting\KategoriKomplainController@update']);
                Route::delete('destroy/{id}', ['as' => 'setting.komplain-kategori.destroy', 'uses' => 'Setting\KategoriKomplainController@destroy']);
            });

            // Tipe Regulasi
            Route::group(['prefix' => 'tipe-regulasi', 'middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.tipe-regulasi.index', 'uses' => 'Setting\TipeRegulasiController@index']);
                Route::get('getdata', ['as' => 'setting.tipe-regulasi.getdata', 'uses' => 'Setting\TipeRegulasiController@getData']);
                Route::get('create', ['as' => 'setting.tipe-regulasi.create', 'uses' => 'Setting\TipeRegulasiController@create']);
                Route::post('store', ['as' => 'setting.tipe-regulasi.store', 'uses' => 'Setting\TipeRegulasiController@store']);
                Route::get('edit/{id}', ['as' => 'setting.tipe-regulasi.edit', 'uses' => 'Setting\TipeRegulasiController@edit']);
                Route::put('update/{id}', ['as' => 'setting.tipe-regulasi.update', 'uses' => 'Setting\TipeRegulasiController@update']);
                Route::delete('destroy/{id}', ['as' => 'setting.tipe-regulasi.destroy', 'uses' => 'Setting\TipeRegulasiController@destroy']);
            });

            // Jenis Penyakit
            Route::group(['prefix' => 'jenis-penyakit','middleware' => ['role:super-admin|admin-puskesmas|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.jenis-penyakit.index', 'uses' => 'Setting\JenisPenyakitController@index']);
                Route::get('getdata', ['as' => 'setting.jenis-penyakit.getdata', 'uses' => 'Setting\JenisPenyakitController@getData']);
                Route::get('create', ['as' => 'setting.jenis-penyakit.create', 'uses' => 'Setting\JenisPenyakitController@create']);
                Route::post('store', ['as' => 'setting.jenis-penyakit.store', 'uses' => 'Setting\JenisPenyakitController@store']);
                Route::get('edit/{id}', ['as' => 'setting.jenis-penyakit.edit', 'uses' => 'Setting\JenisPenyakitController@edit']);
                Route::put('update/{id}', ['as' => 'setting.jenis-penyakit.update', 'uses' => 'Setting\JenisPenyakitController@update']);
                Route::delete('destroy/{id}', ['as' => 'setting.jenis-penyakit.destroy', 'uses' => 'Setting\JenisPenyakitController@destroy']);
            });

            // Tipe Potensi
            Route::group(['prefix' => 'tipe-potensi','middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.tipe-potensi.index', 'uses' => 'Setting\TipePotensiController@index']);
                Route::get('getdata', ['as' => 'setting.tipe-potensi.getdata', 'uses' => 'Setting\TipePotensiController@getData']);
                Route::get('create', ['as' => 'setting.tipe-potensi.create', 'uses' => 'Setting\TipePotensiController@create']);
                Route::post('store', ['as' => 'setting.tipe-potensi.store', 'uses' => 'Setting\TipePotensiController@store']);
                Route::get('edit/{id}', ['as' => 'setting.tipe-potensi.edit', 'uses' => 'Setting\TipePotensiController@edit']);
                Route::put('update/{id}', ['as' => 'setting.tipe-potensi.update', 'uses' => 'Setting\TipePotensiController@update']);
                Route::delete('destroy/{id}', ['as' => 'setting.tipe-potensi.destroy', 'uses' => 'Setting\TipePotensiController@destroy']);
            });

            // Slide
            Route::group(['prefix' => 'slide','middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.slide.index', 'uses' => 'Setting\SlideController@index']);
                Route::get('getdata', ['as' => 'setting.slide.getdata', 'uses' => 'Setting\SlideController@getData']);
                Route::get('create', ['as' => 'setting.slide.create', 'uses' => 'Setting\SlideController@create']);
                Route::post('store', ['as' => 'setting.slide.store', 'uses' => 'Setting\SlideController@store']);
                Route::get('edit/{slide}', ['as' => 'setting.slide.edit', 'uses' => 'Setting\SlideController@edit']);
                Route::get('show/{slide}', ['as' => 'setting.slide.show', 'uses' => 'Setting\SlideController@show']);
                Route::put('update/{slide}', ['as' => 'setting.slide.update', 'uses' => 'Setting\SlideController@update']);
                Route::delete('destroy/{slide}', ['as' => 'setting.slide.destroy', 'uses' => 'Setting\SlideController@destroy']);
            });

            // COA
            Route::group(['prefix' => 'coa','middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.coa.index', 'uses' => 'Setting\COAController@index']);
                Route::get('create', ['as' => 'setting.coa.create', 'uses' => 'Setting\COAController@create']);
                Route::post('store', ['as' => 'setting.coa.store', 'uses' => 'Setting\COAController@store']);
                Route::get('sub_coa/{type_id}', ['as' => 'setting.coa.sub_coa', 'uses' => 'Setting\COAController@get_sub_coa']);
                Route::get('sub_sub_coa/{type_id}/{sub_id}', ['as' => 'setting.coa.sub_sub_coa', 'uses' => 'Setting\COAController@get_sub_sub_coa']);
                Route::get('generate_id/{type_id}/{sub_id}/{sub_sub_id}', ['as' => 'setting.coa.generate_id', 'uses' => 'Setting\COAController@generate_id']);
            });

            Route::group(['prefix' => 'aplikasi','middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.aplikasi.index', 'uses' => 'Setting\AplikasiController@index']);
                Route::get('/edit/{aplikasi}', ['as' => 'setting.aplikasi.edit', 'uses' => 'Setting\AplikasiController@edit']);
                Route::put('/update/{aplikasi}', ['as' => 'setting.aplikasi.update', 'uses' => 'Setting\AplikasiController@update']);
            });

            Route::group(['prefix' => 'info-sistem','middleware' => ['role:super-admin|administrator-website']], function () {
                Route::get('/', ['as' => 'setting.info-sistem', 'uses' => 'LogViewerController@index']);
                Route::get('/linkstorage', ['as' => 'setting.info-sistem.linkstorage', 'uses' => 'LogViewerController@linkStorage']);
            });
        });

        /**
         * Group Routing for Counter
         */
        Route::group(['prefix' => 'counter'], function () {
            Route::get('/', ['as' => 'counter.index', 'uses' => 'Counter\CounterController@index']);
        });
    });

    Route::group(['middleware' => ['web']], function () {
        if (Cookie::get(env('COUNTER_COOKIE', 'kd-counter')) == false) {
            Cookie::queue(env('COUNTER_COOKIE', 'kd-counter'), str_random(80), 2628000); // Forever aka 5 years
        }
    });

    Route::get('/sitemap', 'SitemapController@index');
    Route::get('/sitemap/prosedur', 'SitemapController@prosedur');

    // Semua Desa
    Route::get('/api/desa', function () {
        return DataDesa::paginate(10)->name('api.desa');
    });

    Route::get('/api/list-penduduk', function () {
        return Penduduk::selectRaw('nik as id, nama as text, nik, nama, alamat, rt, rw, tempat_lahir, tanggal_lahir')
            ->whereRaw('lower(nama) LIKE \'%' . strtolower(request('q')) . '%\' or lower(nik) LIKE \'%' . strtolower(request('q')) . '%\'')
            ->paginate(10);
    });

    // TODO : Peserta KK gunakan das_keluarga
    Route::get('/api/list-kk', function () {
        return Penduduk::selectRaw('no_kk as id, nama as text, nik, nama, alamat, rt, rw, tempat_lahir, tanggal_lahir')
            ->whereRaw('lower(nama) LIKE \'%' . strtolower(request('q')) . '%\' or lower(no_kk) LIKE \'%' . strtolower(request('q')) . '%\'')
            ->where('kk_level', 1)
            ->paginate(10);
    });
});
