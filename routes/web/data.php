<?php
/**
    * Group Routing for Data
*/

Route::group(
    [
      'namespace'=>'Data',
      'middleware'=>'sentinel_access:admin', 
      'prefix' => 'data',
    ]
    , function () {
        // Routes Resources Profil
        Route::group(['prefix' => 'profil'], function () {
            Route::get('getdata', ['as' => 'data.profil.getdata', 'uses' => 'ProfilController@getDataProfil']);
            Route::get('/', ['as' => 'data.profil.index', 'uses' => 'ProfilController@index']);
            Route::get('create', ['as' => 'data.profil.create', 'uses' => 'ProfilController@create']);
            Route::post('store', ['as' => 'data.profil.store', 'uses' => 'ProfilController@store']);
            Route::get('edit/{id}', ['as' => 'data.profil.edit', 'uses' => 'ProfilController@edit']);
            Route::put('update/{id}', ['as' => 'data.profil.update', 'uses' => 'ProfilController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.profil.destroy', 'uses' => 'ProfilController@destroy']);
            Route::get('success/{id}', ['as' => 'data.profil.success', 'uses' => 'ProfilController@success']);
            Route::get('show', ['as' => 'data.profil.show', 'uses' => 'ProfilController@show']);
        });

        //Routes Resource Data Umum
        Route::group(['prefix' => 'data-umum'], function () {
            Route::get('getdata', ['as' => 'data.data-umum.getdata', 'uses' => 'DataUmumController@getDataUmum']);
            Route::get('getdataajax', ['as' => 'data.data-umum.getdataajax', 'uses' => 'DataUmumController@getDataUmumAjax']);
            Route::get('/', ['as' => 'data.data-umum.index', 'uses' => 'DataUmumController@index']);
            Route::get('create', ['as' => 'data.data-umum.create', 'uses' => 'DataUmumController@create']);
            Route::post('store', ['as' => 'data.data-umum.store', 'uses' => 'DataUmumController@store']);
            Route::get('show/{id}', ['as' => 'data.data-umum.show', 'uses' => 'DataUmumController@show']);
            Route::get('edit/{id}', ['as' => 'data.data-umum.edit', 'uses' => 'DataUmumController@edit']);
            Route::put('update/{id}', ['as' => 'data.data-umum.update', 'uses' => 'DataUmumController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.data-umum.destroy', 'uses' => 'DataUmumController@destroy']);
        });

        //Routes Resource Data Desa
        Route::group(['prefix' => 'data-desa'], function () {
            Route::get('getdata', ['as' => 'data.data-desa.getdata', 'uses' => 'DataDesaController@getDataDesa']);
            Route::post('getdesa', ['as' => 'data.data-desa.getdesa', 'uses' => 'DataDesaController@getDesaKecamatan']);
            Route::get('/', ['as' => 'data.data-desa.index', 'uses' => 'DataDesaController@index']);
            Route::get('create', ['as' => 'data.data-desa.create', 'uses' => 'DataDesaController@create']);
            Route::post('store', ['as' => 'data.data-desa.store', 'uses' => 'DataDesaController@store']);
            Route::get('show/{id}', ['as' => 'data.data-desa.show', 'uses' => 'DataDesaController@show']);
            Route::get('edit/{id}', ['as' => 'data.data-desa.edit', 'uses' => 'DataDesaController@edit']);
            Route::put('update/{id}', ['as' => 'data.data-desa.update', 'uses' => 'DataDesaController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.data-desa.destroy', 'uses' => 'DataDesaController@destroy']);
        });

        //Routes Resource Penduduk
        Route::group(['prefix' => 'penduduk'], function () {
            Route::get('getdata', ['as' => 'data.penduduk.getdata', 'uses' => 'PendudukController@getPenduduk']);
            Route::get('/', ['as' => 'data.penduduk.index', 'uses' => 'PendudukController@index']);
            Route::post('store', ['as' => 'data.penduduk.store', 'uses' => 'PendudukController@store']);
            Route::get('show/{id}', ['as' => 'data.penduduk.show', 'uses' => 'PendudukController@show']);
            Route::put('update/{id}', ['as' => 'data.penduduk.update', 'uses' => 'PendudukController@update']);
            Route::get('import', ['as' => 'data.penduduk.import', 'uses' => 'PendudukController@import']);
            Route::post('import-excel', ['as' => 'data.penduduk.import-excel', 'uses' => 'PendudukController@importExcel']);
        });
        //Routes Resource Jabatan
        Route::group(['prefix' => 'jabatan'], function () {
            Route::get('getdata', ['as' => 'data.jabatan.getdata', 'uses' => 'JabatanController@getJabatan']);
            Route::get('/', ['as' => 'data.jabatan.index', 'uses' => 'JabatanController@index']);
            Route::get('create', ['as' => 'data.jabatan.create', 'uses' => 'JabatanController@create']);
            Route::post('store', ['as' => 'data.jabatan.store', 'uses' => 'JabatanController@store']);
            Route::get('edit/{id}', ['as' => 'data.jabatan.edit', 'uses' => 'JabatanController@edit']);
            Route::put('update/{id}', ['as' => 'data.jabatan.update', 'uses' => 'JabatanController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.jabatan.destroy', 'uses' => 'JabatanController@destroy']);
        });

        //Routes Resource Pegawai
        Route::group(['prefix' => 'pegawai'], function () {
            Route::get('getdata', ['as' => 'data.pegawai.getdata', 'uses' => 'PegawaiController@getPegawai']);
            Route::get('/', ['as' => 'data.pegawai.index', 'uses' => 'PegawaiController@index']);
            Route::get('create', ['as' => 'data.pegawai.create', 'uses' => 'PegawaiController@create']);
            Route::post('store', ['as' => 'data.pegawai.store', 'uses' => 'PegawaiController@store']);
            Route::get('show/{id}', ['as' => 'data.pegawai.show', 'uses' => 'PegawaiController@show']);
            Route::get('edit/{id}', ['as' => 'data.pegawai.edit', 'uses' => 'PegawaiController@edit']);
            Route::put('update/{id}', ['as' => 'data.pegawai.update', 'uses' => 'PegawaiController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.pegawai.destroy', 'uses' => 'PegawaiController@destroy']);
            Route::get('import', ['as' => 'data.pegawai.import', 'uses' => 'PegawaiController@import']);
            Route::post('import-excel', ['as' => 'data.pegawai.import-excel', 'uses' => 'PegawaiController@importExcel']);
        });

        //Routes Resource Keluarga
        Route::group(['prefix' => 'keluarga'], function () {
            Route::get('getdata', ['as' => 'data.keluarga.getdata', 'uses' => 'KeluargaController@getKeluarga']);
            Route::get('/', ['as' => 'data.keluarga.index', 'uses' => 'KeluargaController@index']);
            Route::get('show/{id}', ['as' => 'data.keluarga.show', 'uses' => 'KeluargaController@show']);
            Route::get('import', ['as' => 'data.keluarga.import', 'uses' => 'KeluargaController@import']);
            Route::post('import-excel', ['as' => 'data.keluarga.import-excel', 'uses' => 'KeluargaController@importExcel']);
        });

        //Routes Resource Laporan Penduduk
        Route::group(['prefix' => 'laporan-penduduk'], function () {
            Route::get('getdata', ['as' => 'data.laporan-penduduk.getdata', 'uses' => 'LaporanPendudukController@getData']);
            Route::get('/', ['as' => 'data.laporan-penduduk.index', 'uses' => 'LaporanPendudukController@index']);
            Route::delete('destroy/{id}', ['as' => 'data.laporan-penduduk.destroy', 'uses' => 'LaporanPendudukController@destroy']);
            Route::get('download{id}', ['as' => 'data.laporan-penduduk.download', 'uses' => 'LaporanPendudukController@download']);
            Route::get('import', ['as' => 'data.laporan-penduduk.import', 'uses' => 'LaporanPendudukController@import']);
            Route::post('do_import', ['as' => 'data.laporan-penduduk.do_import', 'uses' => 'LaporanPendudukController@do_import']);
        });

        //Routes Resource AKI & AKB
        Route::group(['prefix' => 'aki-akb'], function () {
            Route::get('getdata', ['as' => 'data.aki-akb.getdata', 'uses' => 'AKIAKBController@getDataAKIAKB']);
            Route::get('/', ['as' => 'data.aki-akb.index', 'uses' => 'AKIAKBController@index']);
            Route::get('edit/{id}', ['as' => 'data.aki-akb.edit', 'uses' => 'AKIAKBController@edit']);
            Route::put('update/{id}', ['as' => 'data.aki-akb.update', 'uses' => 'AKIAKBController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.aki-akb.destroy', 'uses' => 'AKIAKBController@destroy']);
            Route::get('import', ['as' => 'data.aki-akb.import', 'uses' => 'AKIAKBController@import']);
            Route::post('do_import', ['as' => 'data.aki-akb.do_import', 'uses' => 'AKIAKBController@do_import']);
        });

        //Routes Resource AKI & AKB
        Route::group(['prefix' => 'imunisasi'], function () {
            Route::get('getdata', ['as' => 'data.imunisasi.getdata', 'uses' => 'ImunisasiController@getDataAKIAKB']);
            Route::get('/', ['as' => 'data.imunisasi.index', 'uses' => 'ImunisasiController@index']);
            Route::get('edit/{id}', ['as' => 'data.imunisasi.edit', 'uses' => 'ImunisasiController@edit']);
            Route::put('update/{id}', ['as' => 'data.imunisasi.update', 'uses' => 'ImunisasiController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.imunisasi.destroy', 'uses' => 'ImunisasiController@destroy']);
            Route::get('import', ['as' => 'data.imunisasi.import', 'uses' => 'ImunisasiController@import']);
            Route::post('do_import', ['as' => 'data.imunisasi.do_import', 'uses' => 'ImunisasiController@do_import']);
        });

        //Routes Resource Epidemi Penyakit
        Route::group(['prefix' => 'epidemi-penyakit'], function () {
            Route::get('getdata', ['as' => 'data.epidemi-penyakit.getdata', 'uses' => 'EpidemiPenyakitController@getDataAKIAKB']);
            Route::get('/', ['as' => 'data.epidemi-penyakit.index', 'uses' => 'EpidemiPenyakitController@index']);
            Route::get('edit/{id}', ['as' => 'data.epidemi-penyakit.edit', 'uses' => 'EpidemiPenyakitController@edit']);
            Route::put('update/{id}', ['as' => 'data.epidemi-penyakit.update', 'uses' => 'EpidemiPenyakitController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.epidemi-penyakit.destroy', 'uses' => 'EpidemiPenyakitController@destroy']);
            Route::get('import', ['as' => 'data.epidemi-penyakit.import', 'uses' => 'EpidemiPenyakitController@import']);
            Route::post('do_import', ['as' => 'data.epidemi-penyakit.do_import', 'uses' => 'EpidemiPenyakitController@do_import']);
        });

        //Routes Resource Toilet Sanitasi
        Route::group(['prefix' => 'toilet-sanitasi'], function () {
            Route::get('getdata', ['as' => 'data.toilet-sanitasi.getdata', 'uses' => 'ToiletSanitasiController@getDataAKIAKB']);
            Route::get('/', ['as' => 'data.toilet-sanitasi.index', 'uses' => 'ToiletSanitasiController@index']);
            Route::get('edit/{id}', ['as' => 'data.toilet-sanitasi.edit', 'uses' => 'ToiletSanitasiController@edit']);
            Route::put('update/{id}', ['as' => 'data.toilet-sanitasi.update', 'uses' => 'ToiletSanitasiController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.toilet-sanitasi.destroy', 'uses' => 'ToiletSanitasiController@destroy']);
            Route::get('import', ['as' => 'data.toilet-sanitasi.import', 'uses' => 'ToiletSanitasiController@import']);
            Route::post('do_import', ['as' => 'data.toilet-sanitasi.do_import', 'uses' => 'ToiletSanitasiController@do_import']);
        });

        //Routes Resource Tingkaat Pendidikan
        Route::group(['prefix' => 'tingkat-pendidikan'], function () {
            Route::get('getdata', ['as' => 'data.tingkat-pendidikan.getdata', 'uses' => 'TingkatPendidikanController@getData']);
            Route::get('/', ['as' => 'data.tingkat-pendidikan.index', 'uses' => 'TingkatPendidikanController@index']);
            Route::get('edit/{id}', ['as' => 'data.tingkat-pendidikan.edit', 'uses' => 'TingkatPendidikanController@edit']);
            Route::put('update/{id}', ['as' => 'data.tingkat-pendidikan.update', 'uses' => 'TingkatPendidikanController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.tingkat-pendidikan.destroy', 'uses' => 'TingkatPendidikanController@destroy']);
            Route::get('import', ['as' => 'data.tingkat-pendidikan.import', 'uses' => 'TingkatPendidikanController@import']);
            Route::post('do_import', ['as' => 'data.tingkat-pendidikan.do_import', 'uses' => 'TingkatPendidikanController@do_import']);
        });

        //Routes Resource Putus Sekolah
        Route::group(['prefix' => 'putus-sekolah'], function () {
            Route::get('getdata', ['as' => 'data.putus-sekolah.getdata', 'uses' => 'PutusSekolahController@getDataPutusSekolah']);
            Route::get('/', ['as' => 'data.putus-sekolah.index', 'uses' => 'PutusSekolahController@index']);
            Route::get('edit/{id}', ['as' => 'data.putus-sekolah.edit', 'uses' => 'PutusSekolahController@edit']);
            Route::put('update/{id}', ['as' => 'data.putus-sekolah.update', 'uses' => 'PutusSekolahController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.putus-sekolah.destroy', 'uses' => 'PutusSekolahController@destroy']);
            Route::get('import', ['as' => 'data.putus-sekolah.import', 'uses' => 'PutusSekolahController@import']);
            Route::post('do_import', ['as' => 'data.putus-sekolah.do_import', 'uses' => 'PutusSekolahController@do_import']);
        });

        //Routes Resource Fasilitas PAUD
        Route::group(['prefix' => 'fasilitas-paud'], function () {
            Route::get('getdata', ['as' => 'data.fasilitas-paud.getdata', 'uses' => 'FasilitasPaudController@getDataFasilitasPAUD']);
            Route::get('/', ['as' => 'data.fasilitas-paud.index', 'uses' => 'FasilitasPaudController@index']);
            Route::get('edit/{id}', ['as' => 'data.fasilitas-paud.edit', 'uses' => 'FasilitasPaudController@edit']);
            Route::put('update/{id}', ['as' => 'data.fasilitas-paud.update', 'uses' => 'FasilitasPaudController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.fasilitas-paud.destroy', 'uses' => 'FasilitasPaudController@destroy']);
            Route::get('import', ['as' => 'data.fasilitas-paud.import', 'uses' => 'FasilitasPaudController@import']);
            Route::post('do_import', ['as' => 'data.fasilitas-paud.do_import', 'uses' => 'FasilitasPaudController@do_import']);
        });


        //Routes Resource Program Bantuan
        Route::group(['prefix' => 'program-bantuan'], function () {
            Route::get('getdata', ['as' => 'data.program-bantuan.getdata', 'uses' => 'ProgramBantuanController@getaProgramBantuan']);
            Route::get('/', ['as' => 'data.program-bantuan.index', 'uses' => 'ProgramBantuanController@index']);
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

        //Routes Resource Anggaran Realisasi
        Route::group(['prefix' => 'anggaran-realisasi'], function () {
            Route::get('getdata', ['as' => 'data.anggaran-realisasi.getdata', 'uses' => 'AnggaranRealisasiController@getDataAnggaran']);
            Route::get('/', ['as' => 'data.anggaran-realisasi.index', 'uses' => 'AnggaranRealisasiController@index']);
            Route::get('edit/{id}', ['as' => 'data.anggaran-realisasi.edit', 'uses' => 'AnggaranRealisasiController@edit']);
            Route::put('update/{id}', ['as' => 'data.anggaran-realisasi.update', 'uses' => 'AnggaranRealisasiController@update']);
            Route::delete('destroy/{id}', ['as' => 'data.anggaran-realisasi.destroy', 'uses' => 'AnggaranRealisasiController@destroy']);
            Route::get('import', ['as' => 'data.anggaran-realisasi.import', 'uses' => 'AnggaranRealisasiController@import']);
            Route::post('do_import', ['as' => 'data.anggaran-realisasi.do_import', 'uses' => 'AnggaranRealisasiController@do_import']);
        });

        //Routes Resource Anggaran Desa
        Route::group(['prefix' => 'anggaran-desa'], function () {
            Route::get('getdata', ['as' => 'data.anggaran-desa.getdata', 'uses' => 'AnggaranDesaController@getDataAnggaran']);
            Route::get('/', ['as' => 'data.anggaran-desa.index', 'uses' => 'AnggaranDesaController@index']);
            Route::delete('destroy/{id}', ['as' => 'data.anggaran-desa.destroy', 'uses' => 'AnggaranDesaController@destroy']);
            Route::get('import', ['as' => 'data.anggaran-desa.import', 'uses' => 'AnggaranDesaController@import']);
            Route::post('do_import', ['as' => 'data.anggaran-desa.do_import', 'uses' => 'AnggaranDesaController@do_import']);
        });

        //Routes Resource Laporan Apbdes
        Route::group(['prefix' => 'laporan-apbdes'], function () {
            Route::get('getdata', ['as' => 'data.laporan-apbdes.getdata', 'uses' => 'LaporanApbdesController@getApbdes']);
            Route::get('/', ['as' => 'data.laporan-apbdes.index', 'uses' => 'LaporanApbdesController@index']);
            Route::delete('destroy/{id}', ['as' => 'data.laporan-apbdes.destroy', 'uses' => 'LaporanApbdesController@destroy']);
            Route::get('download{id}', ['as' => 'data.laporan-apbdes.download', 'uses' => 'LaporanApbdesController@download']);
            Route::get('import', ['as' => 'data.laporan-apbdes.import', 'uses' => 'LaporanApbdesController@import']);
            Route::post('do_import', ['as' => 'data.laporan-apbdes.do_import', 'uses' => 'LaporanApbdesController@do_import']);
            Route::get('download/{id}', ['as' => 'data.laporan-apbdes.download', 'uses' => 'LaporanApbdesController@download']);
        });

        //Routes Resource Admin SIKOMA
        Route::group(['prefix' => 'admin-komplain'], function () {
            Route::get('getdata', ['as' => 'admin-komplain.getdata', 'uses' => 'AdminKomplainController@getDataKomplain']);
            Route::get('/', ['as' => 'admin-komplain.index', 'uses' => 'AdminKomplainController@index']);
            Route::get('edit/{id}', ['as' => 'admin-komplain.edit', 'uses' => 'AdminKomplainController@edit']);
            Route::put('update/{id}', ['as' => 'admin-komplain.update', 'uses' => 'AdminKomplainController@update']);
            Route::delete('destroy/{id}', ['as' => 'admin-komplain.destroy', 'uses' => 'AdminKomplainController@destroy']);
            Route::put('setuju/{id}', ['as' => 'admin-komplain.setuju', 'uses' => 'AdminKomplainController@disetujui']);
            Route::get('statistik', ['as' => 'admin-komplain.statistik', 'uses' => 'AdminKomplainController@statistik']);
        });
    });

