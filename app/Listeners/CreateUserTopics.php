<?php

namespace App\Listeners;

use App\Topic;
use Illuminate\Auth\Events\Registered;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class CreateUserTopics
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  Registered  $event
     * @return void
     */
    public function handle(Registered $event)
    {
        Topic::firstOrCreate(['name' => 'user', 'notifiable_type' => 'user', 'notifiable_id' => null]);

        Topic::addTopic('user', $event->user->id);
    }
}
