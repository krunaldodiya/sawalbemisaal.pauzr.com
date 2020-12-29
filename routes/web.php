<?php

use App\QuizRanking;
use App\User;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    $balance = QuizRanking::selectRaw('sum(prize) as total')->whereColumn('user_id', 'users.id');

    $users = User::addSelect(['balance' => $balance])
        ->orderBy('balance', 'DESC')
        ->get();

    return $users;
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
