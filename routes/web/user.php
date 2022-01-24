<?php

Route::namespace('Auth')->group(function () {
    Route::get('login', ['as' => 'login', 'uses' => 'AuthController@index']);
    Route::post('login', ['as' => 'login', 'uses' => 'AuthController@loginProcess']);
});

Route::group(['middleware' => 'sentinel_access:admin'], function () {
    Route::get('logout', ['as' => 'logout', 'uses' => 'Auth\AuthController@logout']);
    Route::get('/dashboard', 'DashboardController')->name('dashboard');
    // Prefix URL for Setting

});