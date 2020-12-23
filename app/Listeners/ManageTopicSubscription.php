<?php

namespace App\Listeners;

use App\Events\TopicSubscribed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ManageTopicSubscription implements ShouldQueue
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
     * @param  TopicSubscribed  $event
     * @return void
     */
    public function handle(TopicSubscribed $event)
    {
        //
    }
}
