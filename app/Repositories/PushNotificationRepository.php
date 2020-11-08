<?php

namespace App\Repositories;

use App\Topic;
use App\User;
use Illuminate\Support\Facades\Http;

class PushNotificationRepository implements PushNotificationRepositoryInterface
{
    public function client()
    {
        return Http::withHeaders([
            "Authorization" => env('FIREBASE_SERVER_KEY')
        ]);
    }

    public function updateTopicSubscriptions($topic, $tokens)
    {
        try {
            $response = $this->client()->post("https://iid.googleapis.com/iid/v1:batchAdd", [
                "to" => "/topics/{$topic->name}",
                "registration_tokens" => $tokens,
            ]);

            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function notify($topic, $data)
    {
        try {
            $response = $this->client()->post("https://fcm.googleapis.com/fcm/send", [
                'to' => $topic,
                'notification' => $data,
                'data' => $data,
            ]);

            return $response->json();
        } catch (\Throwable $th) {
            throw $th;
        }
    }

    public function subscribeToTopic($topic, $user_id)
    {
        $user = User::with('device_tokens')->find($user_id);

        $topic = Topic::where(['name' => $topic])->first();

        // $topic->subscribers()->attach($user);

        $tokens = $user->device_tokens->map(function ($device_token) {
            return $device_token->token;
        });

        if (count($tokens)) {
            dd($tokens);
        }

        if (count($tokens)) {
            return $this->updateTopicSubscriptions($topic, $tokens);
        }
    }
}
