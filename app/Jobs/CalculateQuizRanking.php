<?php

namespace App\Jobs;

use App\Quiz;
use App\Repositories\QuizRepositoryInterface;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class CalculateQuizRanking implements ShouldQueue
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
        $quiz = $this->quiz;

        $host_prize = $quiz->quiz_infos->total_participants * $quiz->quiz_infos->entry_fee * 0.08;

        $quiz_hosting_earning = $quiz->host->createTransaction($host_prize, 'deposit', [
            'points' => [
                'id' => $quiz->host->id,
                'type' => "quiz_hosting_earning"
            ]
        ]);

        $quiz->host->deposit($quiz_hosting_earning->transaction_id);

        return $quizRepositoryInterface->calculateQuizRankings($quiz->id);
    }
}
