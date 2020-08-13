<?php

namespace App\Repositories;

interface QuizRepositoryInterface
{
    public function getQuizById($quiz_id);
    public function generateQuiz($quiz_info_id, $private);
    public function cancelQuiz($quiz);
    public function startQuiz($quiz);
    public function joinQuiz($quiz_id, $user_id);
    public function joinBulkQuiz($quiz_id, $total_participants);
    public function submitQuiz($quiz_id, $meta);
    public function calculateQuizRankings($quiz_id);
}
