<?php

use Illuminate\Support\Facades\Route;

use Carbon\Carbon;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $timings = "07/09/2020 09:30 AM";

    $quiz_starting_time = Carbon::createFromFormat('d/m/Y h:m A', $timings);

    $expiry = $quiz_starting_time->diffInMinutes(now()->format("d/m/Y h:m A"));

    return [
        'expiry' => $expiry,
    ];
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
