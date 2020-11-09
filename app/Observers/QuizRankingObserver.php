<?php

namespace App\Observers;

use App\QuizRanking;
use App\User;

class QuizRankingObserver
{
    /**
     * Handle the quiz ranking "created" event.
     *
     * @param  \App\QuizRanking  $quizRanking
     * @return void
     */
    public function created(QuizRanking $quizRanking)
    {
        $user = User::find($quizRanking->user_id);

        $transaction = $user->createTransaction($quizRanking->prize, 'deposit', [
            'points' => [
                'id' => $user->id,
                'type' => "quiz_won"
            ]
        ]);

        $user->deposit($transaction->transaction_id);
    }

    /**
     * Handle the quiz ranking "updated" event.
     *
     * @param  \App\QuizRanking  $quizRanking
     * @return void
     */
    public function updated(QuizRanking $quizRanking)
    {
        //
    }

    /**
     * Handle the quiz ranking "deleted" event.
     *
     * @param  \App\QuizRanking  $quizRanking
     * @return void
     */
    public function deleted(QuizRanking $quizRanking)
    {
        //
    }

    /**
     * Handle the quiz ranking "restored" event.
     *
     * @param  \App\QuizRanking  $quizRanking
     * @return void
     */
    public function restored(QuizRanking $quizRanking)
    {
        //
    }

    /**
     * Handle the quiz ranking "force deleted" event.
     *
     * @param  \App\QuizRanking  $quizRanking
     * @return void
     */
    public function forceDeleted(QuizRanking $quizRanking)
    {
        //
    }
}
