<?php

use App\Question;
use App\Quiz;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return Quiz::with('host')->where('id', '95b123ad-09bd-482e-b7d9-8432583a91f1')->first();
});
