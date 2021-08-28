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
