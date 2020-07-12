<?php

use App\Question;
use App\Quiz;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    return "test";
});
