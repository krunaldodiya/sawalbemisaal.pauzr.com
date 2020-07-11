<?php

use App\Question;
use App\Quiz;
use Illuminate\Support\Facades\Route;

Route::get('/', function () {
    return view('welcome');
});

Route::get('/test', function () {
    $quiz = Quiz::with('quiz_infos')->find("cf71e04b-1d5e-44c3-97a8-0b1db7794535");

    $quizInfo = $quiz->quiz_infos;

    $all_questions = Question::inRandomOrder()
        ->limit($quizInfo->all_questions_count)
        ->pluck('id')
        ->toArray();

    $answerable_questions = array_rand($all_questions, $quizInfo->answerable_questions_count);

    return $answerable_questions;
});
