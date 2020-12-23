<?php

namespace App\Listeners;

use App\Repositories\PushNotificationRepository;
use App\Topic;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserTopics implements ShouldQueue
{
    public $pushNotificationRepository;

    public function __construct(PushNotificationRepository $pushNotificationRepository)
    {
        $this->pushNotificationRepository = $pushNotificationRepository;
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        Topic::addTopic('user');

        $this->pushNotificationRepository->subscribeToTopic("user", $event->user->id);

        Topic::addTopic('user', $event->user->id);

        $this->pushNotificationRepository->subscribeToTopic("user_{$event->user->id}", $event->user->id);
    }
}
