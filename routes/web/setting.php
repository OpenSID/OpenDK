<?php

Route::group(['prefix' => 'setting'], function () {
    // User Management
    Route::group(['prefix' => 'user'], function () {
        Route::get('getdata', ['as' => 'setting.user.getdata', 'uses' => 'User\UserController@getDataUser']);
        Route::get('/', ['as' => 'setting.user.index', 'uses' => 'User\UserController@index']);
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
    Route::group(['prefix' => 'role'], function () {
        Route::get('getdata', ['as' => 'setting.role.getdata', 'uses' => 'Role\RoleController@getData']);
        Route::get('/', ['as' => 'setting.role.index', 'uses' => 'Role\RoleController@index']);
        Route::get('create', ['as' => 'setting.role.create', 'uses' => 'Role\RoleController@create']);
        Route::post('store', ['as' => 'setting.role.store', 'uses' => 'Role\RoleController@store']);
        Route::get('edit/{id}', ['as' => 'setting.role.edit', 'uses' => 'Role\RoleController@edit']);
        Route::put('update/{id}', ['as' => 'setting.role.update', 'uses' => 'Role\RoleController@update']);
        Route::delete('destroy/{id}', ['as' => 'setting.role.destroy', 'uses' => 'Role\RoleController@destroy']);
    });

    // Komplain Kategori
    Route::group(['prefix' => 'komplain-kategori'], function () {
        Route::get('/', ['as' => 'setting.komplain-kategori.index', 'uses' => 'Setting\KategoriKomplainController@index']);
        Route::get('getdata', ['as' => 'setting.komplain-kategori.getdata', 'uses' => 'Setting\KategoriKomplainController@getData']);
        Route::get('create', ['as' => 'setting.komplain-kategori.create', 'uses' => 'Setting\KategoriKomplainController@create']);
        Route::post('store', ['as' => 'setting.komplain-kategori.store', 'uses' => 'Setting\KategoriKomplainController@store']);
        Route::get('edit/{id}', ['as' => 'setting.komplain-kategori.edit', 'uses' => 'Setting\KategoriKomplainController@edit']);
        Route::put('update/{id}', ['as' => 'setting.komplain-kategori.update', 'uses' => 'Setting\KategoriKomplainController@update']);
        Route::delete('destroy/{id}', ['as' => 'setting.komplain-kategori.destroy', 'uses' => 'Setting\KategoriKomplainController@destroy']);
    });

    // Tipe Regulasi
    Route::group(['prefix' => 'tipe-regulasi'], function () {
        Route::get('/', ['as' => 'setting.tipe-regulasi.index', 'uses' => 'Setting\TipeRegulasiController@index']);
        Route::get('getdata', ['as' => 'setting.tipe-regulasi.getdata', 'uses' => 'Setting\TipeRegulasiController@getData']);
        Route::get('create', ['as' => 'setting.tipe-regulasi.create', 'uses' => 'Setting\TipeRegulasiController@create']);
        Route::post('store', ['as' => 'setting.tipe-regulasi.store', 'uses' => 'Setting\TipeRegulasiController@store']);
        Route::get('edit/{id}', ['as' => 'setting.tipe-regulasi.edit', 'uses' => 'Setting\TipeRegulasiController@edit']);
        Route::put('update/{id}', ['as' => 'setting.tipe-regulasi.update', 'uses' => 'Setting\TipeRegulasiController@update']);
        Route::delete('destroy/{id}', ['as' => 'setting.tipe-regulasi.destroy', 'uses' => 'Setting\TipeRegulasiController@destroy']);
    });

    // Jenis Penyakit
    Route::group(['prefix' => 'jenis-penyakit'], function () {
        Route::get('/', ['as' => 'setting.jenis-penyakit.index', 'uses' => 'Setting\JenisPenyakitController@index']);
        Route::get('getdata', ['as' => 'setting.jenis-penyakit.getdata', 'uses' => 'Setting\JenisPenyakitController@getData']);
        Route::get('create', ['as' => 'setting.jenis-penyakit.create', 'uses' => 'Setting\JenisPenyakitController@create']);
        Route::post('store', ['as' => 'setting.jenis-penyakit.store', 'uses' => 'Setting\JenisPenyakitController@store']);
        Route::get('edit/{id}', ['as' => 'setting.jenis-penyakit.edit', 'uses' => 'Setting\JenisPenyakitController@edit']);
        Route::put('update/{id}', ['as' => 'setting.jenis-penyakit.update', 'uses' => 'Setting\JenisPenyakitController@update']);
        Route::delete('destroy/{id}', ['as' => 'setting.jenis-penyakit.destroy', 'uses' => 'Setting\JenisPenyakitController@destroy']);
    });

    // Tipe Potensi
    Route::group(['prefix' => 'tipe-potensi'], function () {
        Route::get('/', ['as' => 'setting.tipe-potensi.index', 'uses' => 'Setting\TipePotensiController@index']);
        Route::get('getdata', ['as' => 'setting.tipe-potensi.getdata', 'uses' => 'Setting\TipePotensiController@getData']);
        Route::get('create', ['as' => 'setting.tipe-potensi.create', 'uses' => 'Setting\TipePotensiController@create']);
        Route::post('store', ['as' => 'setting.tipe-potensi.store', 'uses' => 'Setting\TipePotensiController@store']);
        Route::get('edit/{id}', ['as' => 'setting.tipe-potensi.edit', 'uses' => 'Setting\TipePotensiController@edit']);
        Route::put('update/{id}', ['as' => 'setting.tipe-potensi.update', 'uses' => 'Setting\TipePotensiController@update']);
        Route::delete('destroy/{id}', ['as' => 'setting.tipe-potensi.destroy', 'uses' => 'Setting\TipePotensiController@destroy']);
    });

    // Slide
    Route::group(['prefix' => 'slide'], function () {
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
    Route::group(['prefix' => 'coa'], function () {
        Route::get('/', ['as' => 'setting.coa.index', 'uses' => 'Setting\COAController@index']);
        Route::get('create', ['as' => 'setting.coa.create', 'uses' => 'Setting\COAController@create']);
        Route::post('store', ['as' => 'setting.coa.store', 'uses' => 'Setting\COAController@store']);
        Route::get('sub_coa/{type_id}', ['as' => 'setting.coa.sub_coa', 'uses' => 'Setting\COAController@get_sub_coa']);
        Route::get('sub_sub_coa/{type_id}/{sub_id}', ['as' => 'setting.coa.sub_sub_coa', 'uses' => 'Setting\COAController@get_sub_sub_coa']);
        Route::get('generate_id/{type_id}/{sub_id}/{sub_sub_id}', ['as' => 'setting.coa.generate_id', 'uses' => 'Setting\COAController@generate_id']);
    });


    // Backup Restore Database Routes

    Route::group(['prefix' => 'backup'], function () {
        Route::post('upload', ['as' => 'setting.backup.upload', 'uses' => 'Setting\BackupController@upload']);
        Route::post('{fileName}/restore', ['as' => 'setting.backup.restore', 'uses' => 'Setting\BackupController@restore']);
        Route::get('{fileName}/dl', ['as' => 'setting.backup.download', 'uses' => 'Setting\BackupController@download']);
    });
    Route::namespace('Setting')->name('setting.')->group(function () {
        Route::resource('backup', 'BackupController', ['except' => ['create', 'show', 'edit']]);

        Route::group(['prefix' => 'aplikasi'], function () {
            Route::get('/', ['as' => 'aplikasi.index', 'uses' => 'AplikasiController@index']);
            Route::get('/edit/{aplikasi}', ['as' => 'aplikasi.edit', 'uses' => 'AplikasiController@edit']);
            Route::put('/update/{aplikasi}', ['as' => 'aplikasi.update', 'uses' => 'AplikasiController@update']);
        });
    });
    Route::get('info-sistem', ['as' => 'setting.info-sistem', 'uses' => 'LogViewerController@index']);

    /**
        * Group Routing for COUNTER
        */
    Route::group(['prefix' => 'counter'], function () {
        Route::get('/', ['as' => 'counter.index', 'uses' => 'Counter\CounterController@index']);
    });
});