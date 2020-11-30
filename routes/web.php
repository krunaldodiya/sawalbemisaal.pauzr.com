<?php

use App\Quiz;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $quiz = Quiz::with('participants', 'quiz_infos', 'rankings', 'answerable_questions')->find($request->quiz_id);

    return response(['quiz' => $quiz[0]->answerable_questions[0]]);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
