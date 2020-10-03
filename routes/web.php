<?php

use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    return 'test';
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
