<?php

namespace App\Listeners;

use App\Events\QuizGenerated;
use App\Jobs\BotCanJoinQuiz;
use App\Jobs\CheckQuizStatus;
use App\Jobs\NotifyBeforeStart;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateQuizNotification
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
        $quiz = $event->quiz;

        NotifyBeforeStart::dispatch($quiz)->delay($quiz->expired_at->subMinutes($quiz->quiz_infos->notify));

        BotCanJoinQuiz::dispatch($quiz)->delay($quiz->expired_at->subMinutes(1));

        CheckQuizStatus::dispatch($quiz)->delay($quiz->expired_at);
    }
}
