<?php

namespace App\Listeners;

use App\Events\QuizGenerated;
use App\Question;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Queue\InteractsWithQueue;

class GenerateQuizQuestions implements ShouldQueue
{
    /**
     * Create the event listener.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Handle the event.
     *
     * @param  QuizGenerated  $event
     * @return void
     */
    public function handle(QuizGenerated $event)
    {
        $quiz = $event->quiz;
        $quizInfo = $quiz->quiz_infos;

        $all_questions = Question::inRandomOrder()
            ->limit($quizInfo->all_questions_count)
            ->pluck('id')
            ->toArray();

        $answerable_questions = array_rand($all_questions, $quizInfo->answerable_questions_count);

        $questions = collect($all_questions)
            ->map(function ($question_id, $question_index) use ($quiz, $answerable_questions) {
                return [
                    'quiz_id' => $quiz->id,
                    'question_id' => $question_id,
                    'is_answerable' => in_array($question_index, $answerable_questions)
                ];
            })
            ->toArray();

        $quiz->questions()->attach($questions);
    }
}
