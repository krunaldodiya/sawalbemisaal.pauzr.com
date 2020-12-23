<?php

namespace App\Listeners;

use App\Events\QuizGenerated;
use App\Quiz;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use App\Notifications\QuizHosted;

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
        $quiz = Quiz::with('host')->find($event->quiz->id);

        $quiz->host->followers->notify(new QuizHosted($quiz));
    }
}
