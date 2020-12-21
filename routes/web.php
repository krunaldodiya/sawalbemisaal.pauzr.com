<?php

use App\Quiz;
use App\User;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;
use Illuminate\Support\Str;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $quiz = Quiz::with('creator')->first();

    return response(['quiz' => $quiz], 200);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
