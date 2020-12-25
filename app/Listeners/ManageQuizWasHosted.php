<?php

namespace App\Listeners;

use App\Events\QuizWasHosted;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\QuizHosted;
use Illuminate\Support\Facades\Notification;

class ManageQuizWasHosted
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
     * @param  QuizWasHosted  $event
     * @return void
     */
    public function handle(QuizWasHosted $event)
    {
        Notification::send($event->host->followers, new QuizHosted($event->quiz));
    }
}
