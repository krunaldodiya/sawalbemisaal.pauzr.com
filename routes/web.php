<?php

use App\Repositories\PushNotificationRepositoryInterface;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (PushNotificationRepositoryInterface $PushNotificationRepositoryInterface) {
    $PushNotificationRepositoryInterface->notify("/topics/all", [
        'title' => 'Quiz will start in few minutes',
        'body' => "Everyone is preparing, are you?",
        'image' => url('images/notify_soon.jpg'),
        'quiz_id' => "quiz123",
    ]);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
