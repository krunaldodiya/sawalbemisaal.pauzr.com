<?php

namespace App\Listeners;

use App\Quiz;

use App\Events\QuizGenerated;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateQuizTitle
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

        $quiz->title = Quiz::generateTitle();

        $quiz->save();
    }
}
