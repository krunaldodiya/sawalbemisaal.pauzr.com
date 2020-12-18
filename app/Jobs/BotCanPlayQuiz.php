<?php

namespace App\Jobs;

use App\Quiz;
use App\Repositories\QuizRepositoryInterface;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Arr;
use Illuminate\Support\Facades\Auth;

class BotCanPlayQuiz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $quiz;
    public $user;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Quiz $quiz, User $user)
    {
        $this->quiz = $quiz;
        $this->user = $user;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(QuizRepositoryInterface $quizRepositoryInterface)
    {
        $quiz = $this->quiz;

        $user = $this->user;

        auth()->loginUsingId($user->id);

        try {
            $quizRepositoryInterface->startQuiz($quiz, $user);

            $meta = $quiz->answerable_questions
                ->map(function ($question) {
                    $translations = $question->translations()->first();
                    $correct_answer = $translations->answer;
                    $random_answers = [
                        'option_1',
                        $correct_answer,
                        'option_2',
                        $correct_answer,
                        'option_3',
                        $correct_answer,
                        'option_4',
                        $correct_answer
                    ];

                    return [
                        'question_id' => $question->id,
                        'current_answer' => Arr::random($random_answers),
                        'seconds' => Arr::random(([1, 2, 3, 4, 5, 6, 7, 8, 9, 10])),
                    ];
                })
                ->toArray();

            $quizRepositoryInterface->submitQuiz($quiz->id, $meta);
        } catch (\Throwable $th) {
            throw $th;
        }
    }
}
