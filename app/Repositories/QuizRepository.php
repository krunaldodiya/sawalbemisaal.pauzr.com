<?php

namespace App\Repositories;

use App\Jobs\CalculateQuizRanking;
use App\Jobs\DeployQuizBots;
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
    public $userRepositoryInterface;

    public function __construct(
        PushNotificationRepositoryInterface $pushNotificationRepositoryInterface,
        UserRepositoryInterface $userRepositoryInterface
    ) {
        $this->pushNotificationRepositoryInterface = $pushNotificationRepositoryInterface;
        $this->userRepositoryInterface = $userRepositoryInterface;
    }

    public function getQuizById($quiz_id)
    {
        return  Quiz::with('host', 'participants.user', 'quiz_infos.prize_distributions', 'rankings', 'answerable_questions')->find($quiz_id);
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
                    'type' => "quiz_suspended"
                ]
            ]);

            $user->deposit($transaction->transaction_id);
        });

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $quiz->id])->first();

        $this->pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title_key' => 'suspended_due_to_less_participants_title',
            'body_key' => 'suspended_due_to_less_participants_body',
            'title' => "Quiz #{$quiz->title} suspended! Due to less participants",
            'body' => 'Don\'t worry, more quizzes loaded for you!',
            'image' => url('images/notify_canceled.png'),
            'quiz_id' => $quiz->id,
            'show_alert_box' => true
        ]);

        return true;
    }

    public function startQuiz($quiz, $user)
    {
        $quiz_participant = $quiz->participants()->where('user_id', $user->id)->first();

        if (!$quiz_participant) {
            throw new Error("not_joined_yet");
        }

        if ($quiz->status === 'finished') {
            throw new Error("quiz_already_finished");
        }

        QuizParticipant::query()
            ->where(['user_id' => $user->id, 'quiz_id' => $quiz->id])
            ->update(['status' => 'started']);

        return true;
    }

    public function quizCanBeStarted($quiz)
    {
        $quiz->update(['status' => 'started']);

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $quiz->id])->first();

        CalculateQuizRanking::dispatch($quiz)->delay($quiz->expired_at->addMinutes(5));

        DeployQuizBots::dispatch($quiz);

        $this->pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title_key' => 'all_the_best_title',
            'body_key' => 'all_the_best_body',
            'title' => 'All the Best!',
            'body' => "Hurry, Start the quiz #{$quiz->title} NOW!",
            'image' => url('images/notify_started.jpg'),
            'quiz_id' => $quiz->id,
            'show_alert_box' => true
        ]);

        return true;
    }

    public function calculateQuizRankings($quiz_id)
    {
        $quiz = $this->getQuizById($quiz_id);

        $quiz
            ->participants()
            ->where(function ($query) {
                return $query
                    ->where('status', 'joined')
                    ->orWhere('status', 'started');
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

                $prize = $participant->status  === 'finished' && $participant->points  > 0 && $prize_distributions ? $prize_distributions['prize'] : 0;

                return [
                    'user_id' => $participant->user_id,
                    'points' => $participant->points,
                    'rank' => $rank,
                    'prize' => $prize,
                ];
            })
            ->toArray();

        $quiz->rankings()->createMany($quiz_rankings);

        $quiz->update(['status' => 'finished']);

        $topic = Topic::where(['notifiable_type' => 'quiz', 'notifiable_id' => $quiz->id])->first();

        $this->pushNotificationRepositoryInterface->notify("/topics/{$topic->name}", [
            'title_key' => 'winners_announced_title',
            'body_key' => 'winners_announced_body',
            'title' => 'Winners Announced',
            'body' => "Check the quiz #{$quiz->title} list, NOW! Congrats winners!",
            'image' => url('images/notify_winners.png'),
            'quiz_id' => $quiz->id,
            'show_alert_box' => true
        ]);

        return true;
    }

    public function submitQuiz($quiz_id, $meta)
    {
        $user = User::with('country')->find(auth()->id());

        $quiz = $this->getQuizById($quiz_id);

        $quiz_participant = $quiz->participants()->where('user_id', $user->id)->first();

        if (!$quiz_participant) {
            throw new Error("not_joined_yet");
        }

        if ($quiz->status === 'finished') {
            throw new Error("quiz_already_finished");
        }

        if ($quiz->status === 'suspended') {
            throw new Error("quiz_already_suspended");
        }

        if ($quiz->status === 'pending') {
            throw new Error("quiz_not_started_yet");
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

        return true;
    }

    public function joinBulkQuiz($quiz_id, $total_participants)
    {
        $ids = QuizParticipant::where('quiz_id', $quiz_id)->pluck('user_id');

        $participants = User::where('demo', true)
            ->whereNotIn('id', $ids)
            ->inRandomOrder()
            ->limit($total_participants)
            ->get();

        return collect($participants)->each(function ($participant) use ($quiz_id) {
            return $this->joinQuiz($quiz_id, $participant->id);
        });
    }

    public function generateQuiz($quiz_info_id, $private)
    {
        $quizInfo = QuizInfo::find($quiz_info_id);

        $quiz = Quiz::create([
            "title" => Quiz::generateTitle(),
            "quiz_info_id" => $quizInfo->id,
            "host_id" => auth()->id(),
            "expired_at" => $quizInfo->expired_at,
            "private" => $private,
        ]);

        $transaction = $quiz->host->createTransaction(2, 'withdraw', [
            'points' => [
                'id' => $quiz->host->id,
                'type' => "quiz_hosting_charge"
            ]
        ]);

        $quiz->host->deposit($transaction->transaction_id);

        return $this->getQuizById($quiz->id);
    }

    public function joinQuiz($quiz_id, $user_id)
    {
        $user = $this->userRepositoryInterface->getUserById($user_id);

        $quiz = $this->getQuizById($quiz_id);

        $quiz_participant = $quiz->participants()->where('user_id', $user->id)->first();

        if ($quiz_participant) {
            throw new Error("quiz_already_joined");
        }

        if ($quiz->status === 'full') {
            throw new Error("quiz_already_full");
        }

        if ($quiz->status === 'suspended') {
            throw new Error("quiz_already_suspended");
        }

        if ($quiz->status === 'finished') {
            throw new Error("quiz_already_finished");
        }

        if ($quiz->status === 'started') {
            throw new Error("quiz_already_started");
        }

        if ($quiz->quiz_infos->entry_fee > $user->wallet->balance) {
            throw new Error("not_enough_wallet_points");
        }

        return $quiz->participants()->create(['user_id' => $user->id]);
    }
}
