<?php 
/**
* Group Routing for Halaman Website
*/

Route::get('/', function () {

});

Route::get('berita', function () {
    return redirect('/');
});

Route::namespace('Page')->group(function () {
    Route::get('/', 'PageController@index')->name('beranda');
    Route::get('berita/{slug}', 'PageController@detailBerita')->name('berita.detail');
    Route::post('komentar', 'PageController@storeKomentar')->name('komentar.store');
    
    Route::group(['prefix' => 'profil'], function () {
        Route::get('letak-geografis', 'ProfilController@LetakGeografis')->name('profil.letak-geografis');
        Route::get('struktur-pemerintahan', 'ProfilController@StrukturPemerintahan')->name('profil.struktur-pemerintahan');
        Route::get('visi-dan-misi', 'ProfilController@VisiMisi')->name('profil.visi-misi');
        Route::get('sejarah', 'ProfilController@sejarah')->name('profil.sejarah');
    });

    Route::get('event/{slug}', 'PageController@eventDetail')->name('event.detail');

    Route::get('desa/desa-{slug}', 'PageController@DesaShow')->name('desa.show');

    Route::get('filter', 'PageController@FilterFeeds')->name('feeds.filter');
    Route::group(['prefix' => 'potensi'], function () {
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
        Route::get('prosedur', 'DownloadController@indexProsedur')->name('unduhan.prosedur');
        Route::get('prosedur/getdata', 'DownloadController@getDataProsedur')->name('unduhan.prosedur.getdata');
        Route::get('prosedur/{nama_prosedur}', 'DownloadController@showProsedur')->name('unduhan.prosedur.show');
        Route::get('prosedur/{file}/download', 'DownloadController@downloadProsedur')->name('unduhan.prosedur.download');

        Route::get('regulasi', 'DownloadController@indexRegulasi')->name('unduhan.regulasi');
        Route::get('regulasi/{nama_regulasi}', 'DownloadController@showRegulasi')->name('unduhan.regulasi.show');
        Route::get('regulasi/{file}/download', 'DownloadController@downloadRegulasi')->name('unduhan.regulasi.download');

        Route::get('form-dokumen', 'DownloadController@indexFormDokumen')->name('unduhan.form-dokumen');
        Route::get('form-dokumen/getdata', 'DownloadController@getDataDokumen')->name('unduhan.form-dokumen.getdata');
    });
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

Route::get('lapak', 'Page\ProdukController@index')->name('lapak.index');

Route::get('/sitemap', 'SitemapController@index');

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