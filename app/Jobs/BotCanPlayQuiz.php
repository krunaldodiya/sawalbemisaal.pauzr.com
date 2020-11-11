<?php

namespace App\Jobs;

use App\Quiz;
use App\User;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
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
    public function handle()
    {
        $quiz = $this->quiz;
        $user = $this->user;

        auth()->loginUsingId($user->id);

        dump(auth()->user()->name);

        // start quiz

        // play quiz
    }
}
