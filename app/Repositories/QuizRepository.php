<?php

namespace App\Repositories;

use App\Jobs\CalculateQuizRanking;
use App\QuestionTranslation;
use App\Quiz;
use App\QuizAnswer;
use App\QuizInfo;
use App\QuizParticipant;
use App\Topic;
use App\User;

use Error;

use Illuminate\Support\Str;

class QuizRepository implements QuizRepositoryInterface
{
    public $pushNotificationRepositoryInterface;

    public function __construct(PushNotificationRepositoryInterface $pushNotificationRepositoryInterface)
    {
        $this->pushNotificationRepositoryInterface = $pushNotificationRepositoryInterface;
    }

    public function getQuizById($quiz_id)
    {
        return  Quiz::with('participants', 'quiz_infos', 'rankings')->find($quiz_id);
    }

    public function cancelQuiz($quiz)
    {
        $quiz->update(['status' => 'suspended']);

        QuizParticipant::where('quiz_id', $quiz->id)->update(['status' => 'suspended']);

        $quiz->participants->each(function ($quiz_participants) use ($quiz) {
            $user = $quiz_participants->user;

            $transaction = $user->createTransaction($quiz->quiz_infos->entry_fee, 'deposit', [
                'points' => [
                    'id' => $user->id,
                    'type' => "Quiz Suspended"
                ]
            ]);

            $user->deposit($transaction->transaction_id);
        });

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $quiz->id])->first();

        $this->pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title' => 'Quiz suspended! Due to less participants',
            'body' => 'Don\'t worry, more quizzes loaded for you!',
            'image' => url('images/notify_canceled.png'),
            'quiz_id' => $quiz->id,
        ]);
    }

    public function startQuiz($quiz)
    {
        $quiz->update(['status' => 'started']);

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $quiz->id])->first();

        CalculateQuizRanking::dispatch($quiz)->delay($quiz->expired_at->addMinutes(5));

        $this->pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title' => 'All the Best!',
            'body' => 'Hurry,Start the quiz NOW!',
            'image' => url('images/notify_started.jpg'),
            'quiz_id' => $quiz->id,
        ]);
    }

    public function calculateQuizRankings($quiz_id)
    {
        $quiz = $this->getQuizById($quiz_id);

        $quiz
            ->participants()
            ->where(function ($query) {
                return $query->where('status', 'joined');
            })
            ->update(['status' => 'missed']);

        $quiz_rankings = $quiz
            ->participants()
            ->orderBy("points", "DESC")
            ->get()
            ->map(function ($participant, $index) use ($quiz) {
                $rank = $index + 1;

                $prize_distributions = collect($quiz->quiz_infos->prize_distributions)
                    ->where('rank', $rank)
                    ->first();

                $prize = $participant->status  === 'finished' && $prize_distributions ? $prize_distributions['prize'] : 0;

                return [
                    'user_id' => $participant->user_id,
                    'rank' => $rank,
                    'prize' => $prize,
                ];
            })
            ->toArray();

        $quiz->rankings()->createMany($quiz_rankings);

        $quiz->update(['status' => 'finished']);

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $quiz->id])->first();

        $this->pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title' => 'Winners Announced',
            'body' => 'Check the list,NOW! Congrats winners!',
            'image' => url('images/notify_winners.png'),
            'quiz_id' => $quiz->id,
        ]);
    }

    public function submitQuiz($quiz_id, $meta)
    {
        $user = User::with('country')->find(auth()->id());

        $quiz = $this->getQuizById($quiz_id);

        $quiz_participant = $quiz->participants()->where('user_id', $user->id)->first();

        if ($quiz_participant->status !== 'finished') {
            throw new Error("Quiz already finished");
        }

        if ($quiz->status !== 'started') {
            throw new Error("Quiz not started yet");
        }

        if ($quiz_participant->status !== 'joined') {
            throw new Error("Quiz not joined yet");
        }

        $answers = collect($meta)
            ->map(function ($answer) use ($user, $quiz) {
                $question_translation = QuestionTranslation::where('question_id', $answer['question_id'])->first();

                $is_correct = $question_translation->answer == $answer['current_answer'];
                $points = $is_correct ? 10 + (1 / $answer['seconds']) : 0;

                return [
                    'id' => Str::uuid(),
                    'user_id' => $user->id,
                    'quiz_id' => $quiz->id,
                    'question_id' => $question_translation->question_id,
                    'points' => $points,
                    'time' => $answer['seconds'],
                    'current_answer' => $answer['current_answer'],
                    'correct_answer' => $question_translation->answer,
                ];
            });

        QuizAnswer::insert($answers->toArray());

        QuizParticipant::where(['quiz_id' => $quiz->id, 'user_id' => $user->id])
            ->update([
                'points' => $answers->sum('points'),
                'status' => 'finished',
            ]);
    }

    public function generateQuiz($quiz_info_id)
    {
        $quizInfo = QuizInfo::find($quiz_info_id);

        return Quiz::create([
            "quiz_info_id" => $quizInfo->id,
            "host_id" => auth()->id(),
            "expired_at" => now()->addMinutes($quizInfo->expiry)
        ]);
    }

    public function joinQuiz($quiz_id, $user_id)
    {
        $user = User::with('country')->find($user_id);

        $quiz = $this->getQuizById($quiz_id);

        $quiz_participant = $quiz->participants()->where('user_id', $user->id)->first();

        if ($quiz_participant) {
            throw new Error("You have already joined the quiz");
        }

        if ($quiz->status !== 'pending') {
            throw new Error("Can't join now, Quiz is already {$quiz->status}");
        }

        if ($quiz->quiz_infos->entry_fee > $user->wallet->balance) {
            throw new Error("Not Enough wallet points");
        }

        $quiz->participants()->create(['user_id' => $user->id]);

        return true;
    }
}
