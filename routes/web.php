<?php

use App\Notifications\QuizHosted;
use App\Quiz;
use App\User;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $quiz = Quiz::with('host')->find($request->quiz_id);

    $host = User::find($quiz->host_id);

    $auth = User::where('email', 'kunal.dodiya1@gmail.com')->first();

    $auth->notify(new QuizHosted($quiz));

    return compact('quiz');
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
