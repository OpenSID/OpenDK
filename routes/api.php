<?php

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

Route::group(['prefix' => 'v1'], function() {
    /**
     * Authentication api
     */
    Route::group(['prefix' => 'auth'], function() {
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
});

/**
     *
     * Grouep Routing API Internal for Select2
     */
    
    //Users JSON
    Route::get('/users', function () {
        return \App\Models\User::where('first_name', 'LIKE', '%' . request('q') . '%')->paginate(10);
    });
    
    // All Provinsi Select2
    Route::get('/provinsi', function () {
        return \App\Models\Wilayah::whereRaw('LENGTH(kode) = 2')->where('nama', 'LIKE', '%' . strtoupper(request('q')) . '%')->paginate(10);
    });
    
    // All Kabupaten Select2
    Route::get('/kabupaten', function () {
        return \App\Models\Wilayah::whereRaw('LENGTH(kode) = 5')->where('nama', 'LIKE', '%' . strtoupper(request('q')) . '%')->paginate(10);
    });
    
    //  All Kecamatan Select2
    Route::get('/kecamatan', function () {
        return \App\Models\Wilayah::whereRaw('LENGTH(kode) = 8')->where('nama', 'LIKE', '%' . strtoupper(request('q')) . '%')->paginate(10);
    });
    
    // All Desa Select2
    Route::get('/desa', function () {
        return \App\Models\Wilayah::whereRaw('LENGTH(kode) = 13')->where('nama', 'LIKE', '%' . strtoupper(request('q')) . '%')->paginate(10);
    });
    
    // Desa Select2 By Kecamatan ID
    Route::get('/desa-by-kid', function () {
        return DB::table('ref_desa')->select('kode', 'nama')->whereRaw('LENGTH(kode) = 2')->where('kecamatan_id', '=', strtoupper(request('kid')))->get();
    })->name('api.desa-by-kid');
    
    // All Profil Select2
    Route::get('/profil', function () {
        return DB::table('das_profil')
            ->join('ref_wilayah', 'das_profil.kecamatan_id', '=', 'ref_wilayah.kode')
            ->select('ref_wilayah.kode', 'ref_kecamatan.nama')
            ->where('ref_wilayah.nama', 'LIKE', '%' . strtoupper(request('q')) . '%')
            ->paginate(10);
    })->name('api.profil');
    
    // Profil By id
    Route::get('/profil-byid', function () {
        return DB::table('das_profil')
            ->join('ref_kecamatan', 'das_profil.kecamatan_id', '=', 'ref_kecamatan.id')
            ->select('ref_kecamatan.id', 'ref_kecamatan.nama')
            ->where('ref_kecamatan.id', '=', request('id'))->get();
    })->name('api.profil-byid');
    
    // All Penduduk Select2
    Route::get('/penduduk', function () {
        return \App\Models\Penduduk::where('nama', 'LIKE', '%' . strtoupper(request('q')) . '%')->paginate(10);
    })->name('api.penduduk');
    
    // Penduduk By id
    Route::get('/penduduk-byid', function () {
        return DB::table('das_penduduk')
            ->where('id', '=', request('id'))->get();
    })->name('api.penduduk-byid');

    Route::get('/test', function () {
        $return = [];
        $a = ['year' => 2018];
        $return = array_merge($return, $a);
        $b = ['penyakit1' => 23];
        $return = array_merge($return, $b);
        $c = ['penyakit2' => 23];
        $return = array_merge($return, $c);

        return $return;
    })->name('api.test');
    

    Route::get('/list-peserta-penduduk', function () {
        return \App\Models\Penduduk::selectRaw('nik as id, nama as text, nik, nama, alamat, rt, rw, tempat_lahir, tanggal_lahir')
            ->whereRaw('lower(nama) LIKE \'%' . strtolower(request('q')) . '%\' or lower(nik) LIKE \'%' . strtolower(request('q')) . '%\'')->paginate(10);
    });

    Route::get('/list-peserta-kk', function () {
        return \App\Models\Penduduk::selectRaw('no_kk as id, nama as text, nik, nama, alamat, rt, rw, tempat_lahir, tanggal_lahir')
            ->whereRaw('lower(nama) LIKE \'%' . strtolower(request('q')) . '%\' or lower(no_kk) LIKE \'%' . strtolower(request('q')) . '%\'')
            ->where('kk_level', 1)->paginate(10);
    });