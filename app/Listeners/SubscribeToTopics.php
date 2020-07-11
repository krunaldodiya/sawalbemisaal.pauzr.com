<?php

namespace App\Listeners;

use App\Repositories\PushNotificationRepository;
use App\Topic;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class SubscribeToTopics
{
    public $pushNotificationRepository;

    public function __construct(PushNotificationRepository $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;
    }

    public function handle(Registered $event)
    {
        $user = $event->user;

        $this->pushNotificationRepository->subscribeToTopic("user", $user->id);
        $this->pushNotificationRepository->subscribeToTopic("user_{$user->id}", $user->id);
    }
}
