<?php

use App\Repositories\QuizRepositoryInterface;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request, QuizRepositoryInterface $quizRepositoryInterface) {
    return 'test';
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
