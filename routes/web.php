<?php

use App\Notifications\QuizHosted;
use App\Quiz;
use App\User;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Notification;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $quiz = Quiz::with('host.followers')->find($request->quiz_id);

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
