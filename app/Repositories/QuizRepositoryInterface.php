<?php

namespace App\Repositories;

interface QuizRepositoryInterface
{
    public function getQuizById($quiz_id);
    public function generateQuiz($quiz_info_id);
    public function cancelQuiz($quiz);
    public function startQuiz($quiz);
    public function joinQuiz($quiz_id, $user_id);
    public function submitQuiz($quiz_id, $meta);
    public function calculateQuizRankings($quiz_id);
}
