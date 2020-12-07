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
    $period = $request->period;

    $user = User::with(['quiz_rankings' => function ($query) use ($period) {
        return $query->where(function ($query) use ($period) {
            return $query->where('created_at', '>=', $period);
        });
    }])->find($request->user_id);

    return response(['user' => $user, 'period' => User::filterPeriod($period)]);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
