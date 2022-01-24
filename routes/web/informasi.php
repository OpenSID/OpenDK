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
 * @package	    OpenDK
 * @author	    Tim Pengembang OpenDesa
 * @copyright	Hak Cipta 2017 - 2022 Perkumpulan Desa Digital Terbuka (https://opendesa.id)
 * @license    	http://www.gnu.org/licenses/gpl.html    GPL V3
 * @link	    https://github.com/OpenSID/opendk
 */

Route::group(
    [
      'namespace'=>'Informasi',
      'middleware'=>'sentinel_access:admin',
      'prefix' => 'informasi',
    ],
    function () {

    /**
        * Group Routing for Polling
        */
        Route::get('/admin', ['as' => 'poll.home', 'uses' => 'PollManagerController@home']);
        Route::get('/admin/polls', ['as' => 'poll.index', 'uses' => 'PollManagerController@index']);
        Route::get('/admin/polls/create', ['as' => 'poll.create', 'uses' => 'PollManagerController@create']);
        Route::get('/admin/polls/{poll}', ['as' => 'poll.edit', 'uses' => 'PollManagerController@edit']);
        Route::patch('/admin/polls/{poll}', ['as' => 'poll.update', 'uses' => 'PollManagerController@update']);
        Route::delete('/admin/polls/{poll}', ['as' => 'poll.remove', 'uses' => 'PollManagerController@remove']);
        Route::patch('/admin/polls/{poll}/lock', ['as' => 'poll.lock', 'uses' => 'PollManagerController@lock']);
        Route::patch('/admin/polls/{poll}/unlock', ['as' => 'poll.unlock', 'uses' => 'PollManagerController@unlock']);
        Route::post('/admin/polls', ['as' => 'poll.store', 'uses' => 'PollManagerController@store']);
        Route::get('/admin/polls/result/{poll}', ['as' => 'poll.result', 'uses' => 'PollManagerController@result']);

        //Routes for produk resource
        Route::group(['prefix' => 'produk'], function () {
            Route::get('/', ['as' => 'informasi.produk.index', 'uses' => 'ProdukController@index']);
            Route::get('show/{produk}', ['as' => 'informasi.produk.show', 'uses' => 'ProdukController@show']);
            Route::get('getdata', ['as' => 'informasi.produk.getdata', 'uses' => 'ProdukController@getDataProduk']);
            Route::get('create', ['as' => 'informasi.produk.create', 'uses' => 'ProdukController@create']);
            Route::post('store', ['as' => 'informasi.produk.store', 'uses' => 'ProdukController@store']);
            Route::get('edit/{produk}', ['as' => 'informasi.produk.edit', 'uses' => 'ProdukController@edit']);
            Route::put('update/{produk}', ['as' => 'informasi.produk.update', 'uses' => 'ProdukController@update']);
            Route::delete('destroy/{produk}', ['as' => 'informasi.produk.destroy', 'uses' => 'ProdukController@destroy']);
        });

        //Routes for prosedur resource
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

        //Routes for Regulasi resources
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

        //Routes for FAQ resources
        Route::group(['prefix' => 'faq'], function () {
            Route::get('/', ['as' => 'informasi.faq.index', 'uses' => 'FaqController@index']);
            Route::get('show/{id}', ['as' => 'informasi.faq.show', 'uses' => 'FaqController@show']);
            Route::get('create', ['as' => 'informasi.faq.create', 'uses' => 'FaqController@create']);
            Route::post('store', ['as' => 'informasi.faq.store', 'uses' => 'FaqController@store']);
            Route::get('edit/{id}', ['as' => 'informasi.faq.edit', 'uses' => 'FaqController@edit']);
            Route::post('update/{id}', ['as' => 'informasi.faq.update', 'uses' => 'FaqController@update']);
            Route::delete('destroy/{id}', ['as' => 'informasi.faq.destroy', 'uses' => 'FaqController@destroy']);
        });

        //Routes for Events resources
        Route::group(['prefix' => 'event'], function () {
            Route::get('/', ['as' => 'informasi.event.index', 'uses' => 'EventController@index']);
            Route::get('show/{event}', ['as' => 'informasi.event.show', 'uses' => 'EventController@show']);
            Route::get('create', ['as' => 'informasi.event.create', 'uses' => 'EventController@create']);
            Route::post('store', ['as' => 'informasi.event.store', 'uses' => 'EventController@store']);
            Route::get('edit/{event}', ['as' => 'informasi.event.edit', 'uses' => 'EventController@edit']);
            Route::post('update/{event}', ['as' => 'informasi.event.update', 'uses' => 'EventController@update']);
            Route::delete('destroy/{event}', ['as' => 'informasi.event.destroy', 'uses' => 'EventController@destroy']);
        });

        //Routes for artikel resources
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

        //Routes for Form Dokumen resources
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

        //Routes for Potensi resources
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
    }
);
