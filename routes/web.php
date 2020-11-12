<?php

use App\Quiz;
use App\Repositories\PushNotificationRepositoryInterface;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request, PushNotificationRepositoryInterface $pushNotificationRepositoryInterface) {
    $quiz_id = "286b7555-2e77-410c-9a71-abf59ce83412";
    $quiz = Quiz::find($quiz_id);

    $pushNotificationRepositoryInterface->notify("/topics/quiz_{$quiz->id}", [
        'key' => $request->key,
        'title' => "Quiz #{$quiz->title} will start in few minutes",
        'body' => "Everyone is preparing, are you?",
        'image' => url('images/notify_soon.jpg'),
        'quiz_id' => $quiz->id,
    ]);

    return 'test';
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
