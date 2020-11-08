<?php

use App\QuizParticipant;
use App\Repositories\PushNotificationRepositoryInterface;
use App\Topic;
use Illuminate\Support\Facades\Route;

use Illuminate\Http\Request;

Route::get('/', function (Request $request) {
    return view('welcome');
});

Route::get('/test', function (Request $request) {
    return 'test';
});

Route::get('/test/push', function (
    Request $request,
    PushNotificationRepositoryInterface $pushNotificationRepositoryInterface
) {
    $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $request->quiz_id])->first();

    $pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
        'title' => 'test',
        'body' => 'test',
        'image' => url('images/notify_winners.png'),
    ]);
});

Route::get('/test/subscribe', function (
    Request $request,
    PushNotificationRepositoryInterface $pushNotificationRepositoryInterface
) {
    $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $request->quiz_id])->first();

    return $pushNotificationRepositoryInterface->subscribeToTopic($topic->name, $request->user_id);
});

Route::get('/refer', function (Request $request) {
    if ($request->has('utm_id')) {
        $request->session()->put('utm_id', $request->get('utm_id'));
    }

    return redirect("https://www.sawalbemisaal.com");
});

Route::get('/media/{media}', 'HomeController@getMediaFile');
