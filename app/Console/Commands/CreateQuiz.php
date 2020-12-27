<?php

namespace App\Console\Commands;

use App\User;
use App\QuizInfo;

use App\Repositories\QuizRepositoryInterface;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;

class CreateQuiz extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'create:quiz';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'create quiz';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle(QuizRepositoryInterface $quizRepositoryInterface)
    {
        $author = User::where('email', 'Antriksh93@gmail.com')->first();

        Auth::loginUsingId($author->id);

        $quizInfos = QuizInfo::where('auto', true)->get();

        collect($quizInfos)->each(function ($quizInfo) use ($quizRepositoryInterface) {
            return $quizRepositoryInterface->generateQuiz($quizInfo->id, false);
        });
    }
}
