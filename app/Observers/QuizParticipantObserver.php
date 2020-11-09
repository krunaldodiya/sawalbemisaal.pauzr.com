<?php

namespace App\Observers;

use App\Quiz;
use App\QuizParticipant;
use App\Repositories\PushNotificationRepositoryInterface;
use App\Topic;
use App\User;

class QuizParticipantObserver
{
    public $pushNotificationRepositoryInterface;

    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepositoryInterface)
    {
        $this->pushNotificationRepositoryInterface = $pushNotificationRepositoryInterface;
    }

    /**
     * Handle the quiz participant "created" event.
     *
     * @param  \App\QuizParticipant  $quizParticipant
     * @return void
     */
    public function created(QuizParticipant $quizParticipant)
    {
        $user = User::with('country')->find($quizParticipant->user_id);

        $quiz = Quiz::with('participants', 'quiz_infos')->find($quizParticipant->quiz_id);

        if ($quiz->participants->count() >= $quiz->quiz_infos->total_participants) {
            $quiz->update(['status' => 'full']);
        }

        $topic = Topic::where('name', "quiz_{$quiz->id}")->first();

        $this->pushNotificationRepositoryInterface->subscribeToTopic($topic->name, $user->id);

        $transaction = $user->createTransaction($quiz->quiz_infos->entry_fee, 'withdraw', [
            'points' => [
                'id' => $user->id,
                'type' => "joined_quiz"
            ]
        ]);

        $user->deposit($transaction->transaction_id);
    }

    /**
     * Handle the quiz participant "updated" event.
     *
     * @param  \App\QuizParticipant  $quizParticipant
     * @return void
     */
    public function updated(QuizParticipant $quizParticipant)
    {
        //
    }

    /**
     * Handle the quiz participant "deleted" event.
     *
     * @param  \App\QuizParticipant  $quizParticipant
     * @return void
     */
    public function deleted(QuizParticipant $quizParticipant)
    {
        //
    }

    /**
     * Handle the quiz participant "restored" event.
     *
     * @param  \App\QuizParticipant  $quizParticipant
     * @return void
     */
    public function restored(QuizParticipant $quizParticipant)
    {
        //
    }

    /**
     * Handle the quiz participant "force deleted" event.
     *
     * @param  \App\QuizParticipant  $quizParticipant
     * @return void
     */
    public function forceDeleted(QuizParticipant $quizParticipant)
    {
        //
    }
}
