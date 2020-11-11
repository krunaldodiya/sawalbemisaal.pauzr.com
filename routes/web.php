<?php

use App\Repositories\PushNotificationRepositoryInterface;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request, PushNotificationRepositoryInterface $pushNotificationRepositoryInterface) {
    $pushNotificationRepositoryInterface->notify("/topics/quiz_c3b21b58-27c1-4c43-803f-de67e88c4305", [
        'key' => $request->key,
        'title' => 'Quiz will start in few minutes',
        'body' => "Everyone is preparing, are you?",
        'image' => url('images/notify_soon.jpg'),
        'quiz_id' => "c3b21b58-27c1-4c43-803f-de67e88c4305",
    ]);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
