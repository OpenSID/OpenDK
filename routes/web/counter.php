<?php

Route::group(['middleware' => ['web']], function () {
    if (Cookie::get(env('COUNTER_COOKIE', 'kd-counter')) == false) {
        Cookie::queue(env('COUNTER_COOKIE', 'kd-counter'), str_random(80), 2628000); // Forever aka 5 years
    }
});