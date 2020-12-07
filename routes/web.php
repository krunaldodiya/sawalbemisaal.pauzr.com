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
    $period = User::filterPeriod($request->period);

    $users = User::query()
        ->with([
            'country',
            'wallet.transactions',
            'quiz_rankings' => function ($query) use ($period) {
                return $query
                    ->where('created_at', '>=', $period)
                    ->where('prize', '>', 0);
            }
        ])
        ->get();

    $rankings = $users
        ->map(function ($user) use ($period) {
            return [
                'user' => $user,
                'prize' => $user->quiz_rankings->sum('prize'),
                'period' => $period
            ];
        })
        ->toArray();

    return response(['rankings' => $rankings], 200);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/app/download', "HomeController@downloadApp");
Route::get('/media/{media}', 'HomeController@getMediaFile');
