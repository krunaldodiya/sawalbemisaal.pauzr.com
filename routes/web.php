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

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
