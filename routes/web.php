<?php

use App\Quiz;
use App\Repositories\QuizRepositoryInterface;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request, QuizRepositoryInterface $quizRepositoryInterface) {
    $quiz = Quiz::with('rankings')->whereHas('rankings', function ($query) {
        return $query->where('prize', 0)->where("user_id", "d4bcd8af-ef7f-4b5b-b423-484d06672ea3");
    })->get();

    return $quiz;
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
