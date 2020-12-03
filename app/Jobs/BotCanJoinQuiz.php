<?php

namespace App\Jobs;

use App\Repositories\QuizRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class BotCanJoinQuiz implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $quiz;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct($quiz)
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
        $quiz = $this->quiz;

        $total_participants = $quiz->quiz_infos->total_participants - $quiz->participants->count();

        $quizRepositoryInterface->joinBulkQuiz($quiz->id, $total_participants);
    }
}
