<?php

use Illuminate\Support\Facades\Route;

use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    //
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
