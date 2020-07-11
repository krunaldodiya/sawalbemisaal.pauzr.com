<?php

namespace App\Jobs;

use App\Quiz;
use App\Repositories\QuizRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CheckQuizStatus implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $quiz;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct(Quiz $quiz)
    {
        $this->quiz = $quiz;
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle(QuizRepositoryInterface $quizRepositoryInterface)
    {
        $quiz = Quiz::with('participants', 'quiz_infos', 'rankings')->find($this->quiz->id);

        if ($quiz->participants()->count() < $quiz->quiz_infos->required_participants) {
            return $quizRepositoryInterface->cancelQuiz($quiz);
        }

        return $quizRepositoryInterface->startQuiz($quiz);
    }
}
