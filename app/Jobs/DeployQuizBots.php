<?php

namespace App\Jobs;

use App\Quiz;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeployQuizBots implements ShouldQueue
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
    public function handle()
    {
        $quiz = $this->quiz;

        $quiz
            ->participants()
            ->whereHas('user', function ($query) {
                return $query->where('demo', true);
            })
            ->get()
            ->each(function ($participant) use ($quiz) {
                BotCanPlayQuiz::dispatch($quiz, $participant->user)->delay(now()->addSeconds(5));
            });
    }
}
