<?php

namespace App\Repositories;

interface QuizRepositoryInterface
{
    public function getQuizById($quiz_id);
    public function generateQuiz($forceGenerate, $quiz_info_id, $host_id);
    public function cancelQuiz($quiz);
    public function joinQuiz($quiz_id, $user_id);
    public function startQuiz($quiz_id);
    public function submitQuiz($quiz_id, $meta);
    public function calculateQuizRankings($quiz_id);
}
