<?php

namespace App\Listeners;

use App\Events\UserWasFollowed;
use App\Notifications\UserFollowed;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class ManageUserWasFollowed
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
     * @param  UserWasFollowed  $event
     * @return void
     */
    public function handle(UserWasFollowed $event)
    {
        $event->following->notify(new UserFollowed($event->following->toArray(), $event->follower->toArray()));
    }
}
