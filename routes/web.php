<?php

use App\Quiz;
use App\QuizParticipant;
use App\Repositories\PushNotificationRepositoryInterface;
use App\Topic;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $quiz = Quiz::first();

    $participants = $quiz->participants()->whereHas('user', function ($query) {
        return $query->where('demo', true);
    });

    return $participants;
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
