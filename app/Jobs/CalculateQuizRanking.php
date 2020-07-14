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
        $host_prize = $this->quiz->total_participants * $this->quiz->entry_fee * 0.10;

        $transaction = $this->quiz->host->createTransaction($host_prize, 'deposit', [
            'points' => [
                'id' => $this->quiz->host->id,
                'type' => "Quiz Hosted"
            ]
        ]);

        $this->quiz->host->deposit($transaction->transaction_id);

        return $quizRepositoryInterface->calculateQuizRankings($this->quiz->id);
    }
}
