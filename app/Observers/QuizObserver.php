<?php

namespace App\Observers;

use App\Quiz;
use App\Events\QuizGenerated;

class QuizObserver
{
    /**
     * Handle the quiz "created" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function created(Quiz $quiz)
    {
        event(new QuizGenerated($quiz));
    }

    /**
     * Handle the quiz "updated" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function updated(Quiz $quiz)
    {
        //
    }

    /**
     * Handle the quiz "deleted" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function deleted(Quiz $quiz)
    {
        //
    }

    /**
     * Handle the quiz "restored" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function restored(Quiz $quiz)
    {
        //
    }

    /**
     * Handle the quiz "force deleted" event.
     *
     * @param  \App\Quiz  $quiz
     * @return void
     */
    public function forceDeleted(Quiz $quiz)
    {
        //
    }
}
