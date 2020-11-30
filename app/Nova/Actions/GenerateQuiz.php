<?php

namespace App\Nova\Actions;

use App\Repositories\QuizRepositoryInterface;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Support\Collection;

use Laravel\Nova\Actions\Action;
use Laravel\Nova\Fields\ActionFields;

class GenerateQuiz extends Action
{
    use InteractsWithQueue, Queueable;

    public $onlyOnIndex = true;

    public $quizRepositoryInterface;

    public function __construct(QuizRepositoryInterface $quizRepositoryInterface)
    {
        $this->quizRepositoryInterface = $quizRepositoryInterface;
    }

    /**
     * Perform the action on the given models.
     *
     * @param  \Laravel\Nova\Fields\ActionFields  $fields
     * @param  \Illuminate\Support\Collection  $models
     * @return mixed
     */
    public function handle(ActionFields $fields, Collection $models)
    {
        $quiz_info = $models[0];

        $this->quizRepositoryInterface->generateQuiz($quiz_info->id, false);

        return Action::message("Quiz has been generated");
    }

    /**
     * Get the fields available on the action.
     *
     * @return array
     */
    public function fields()
    {
        return [];
    }
}
