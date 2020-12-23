<?php

namespace App\Listeners;

use App\Events\QuizGenerated;
use App\Quiz;
use App\User;
use App\Notifications\QuizHosted;

use Illuminate\Support\Facades\Notification;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class NotifyFollowers implements ShouldQueue
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
     * @param  QuizGenerated  $event
     * @return void
     */
    public function handle(QuizGenerated $event)
    {
        $quiz = Quiz::with('host.followers')->find($event->quiz->id);

        $host = User::with('followers')->find($quiz->host_id);

        Notification::send($host->followers, new QuizHosted($quiz));
    }
}
