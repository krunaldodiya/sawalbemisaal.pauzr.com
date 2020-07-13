<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateQuiz;
use App\Http\Requests\JoinQuiz;
use App\Http\Requests\QuizInfo;
use App\Http\Requests\SubmitQuiz;
use App\Http\Requests\HostQuiz;

use App\QuestionTranslation;
use App\Quiz;
use App\QuizAnswer;
use App\QuizRanking;
use App\Repositories\QuizRepositoryInterface;

use Illuminate\Http\Request;

class QuizController extends Controller
{
    public $quizRepositoryInterface;

    public function __construct(QuizRepositoryInterface $quizRepositoryInterface)
    {
        $this->quizRepositoryInterface = $quizRepositoryInterface;
    }

    public function host(HostQuiz $request)
    {
        $prizes = config('prizes');

        $total_winners = count($prizes[$request->total_participants]);

        $quizInfo = QuizInfo::create([
            'auto' => false,
            'entry_fee' => $request->entry_fee,
            'total_participants' => $request->total_participants,
            'required_participants' => $request->total_participants,
            'all_questions_count' => 50,
            'answerable_questions_count' => 10,
            'total_winners' => $total_winners,
            'expiry' => $request->expiry,
            'notify' => 15,
            'time' => 10
        ]);

        $quiz = $this->quizRepositoryInterface->generateQuiz(true, $quizInfo->id, auth()->id());

        return response(['quiz' => $quiz], 200);
    }

    public function generate(GenerateQuiz $request)
    {
        $quiz = $this->quizRepositoryInterface->generateQuiz(true, $request->quiz_info_id, $request->user_id);

        return response(['quiz' => $quiz], 200);
    }

    public function join(JoinQuiz $request)
    {
        $this->quizRepositoryInterface->joinQuiz($request->quiz_id, $request->user_id);

        return response(['status' => "done"], 200);
    }

    public function submit(SubmitQuiz $request)
    {
        $this->quizRepositoryInterface->submitQuiz($request->quiz_id, $request->meta);

        return response(['status' => "done"], 200);
    }

    public function getActiveQuizzes(Request $request)
    {
        $quizzes = Quiz::with('quiz_infos', 'participants', 'questions')
            ->where('expired_at', '>=', now())
            ->orWhere('status', 'started')
            ->orderBy('expired_at', 'asc')
            ->get();

        return response(['quizzes' => $quizzes], 200);
    }

    public function getUserQuizzes(Request $request)
    {
        $quizzes = Quiz::with('quiz_infos', 'participants', 'questions')
            ->whereHas('participants', function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->orderBy('expired_at', 'asc')
            ->get();

        return response(['quizzes' => $quizzes], 200);
    }

    public function getQuizById(QuizInfo $request)
    {
        $quiz = $this->quizRepositoryInterface->getQuizById($request->quiz_id);

        return response(['quiz' => $quiz], 200);
    }

    public function getQuizWinners(QuizInfo $request)
    {
        $winners = QuizRanking::with('quiz', 'user')
            ->where('quiz_id', $request->quiz_id)
            ->orderBy('rank', 'asc')
            ->get();

        return response(['winners' => $winners], 200);
    }

    public function getQuizAnswers(QuizInfo $request)
    {
        $answers = QuizAnswer::with('question')
            ->where(['quiz_id' => $request->quiz_id, 'user_id' => auth()->id()])
            ->orderBy('rank', 'asc')
            ->get();

        return response(['answers' => $answers], 200);
    }

    public function getAllQuestions(QuizInfo $request)
    {
        $user = auth()->user();

        $language_id = $user->language_id;

        $quiz = Quiz::with('questions')->find($request->quiz_id);

        $questions_list = $quiz
            ->questions()
            ->pluck('id')
            ->toArray();

        $questions = QuestionTranslation::where('language_id', $language_id)
            ->whereIn('question_id', $questions_list)
            ->get();

        return response(['questions' => $questions], 200);
    }

    public function getAnswerableQuestions(QuizInfo $request)
    {
        $user = auth()->user();

        $language_id = $user->language_id;

        $quiz = Quiz::with('questions')->find($request->quiz_id);

        $questions_list = $quiz
            ->questions()
            ->where('is_answerable', true)
            ->pluck('id')
            ->toArray();

        $questions = QuestionTranslation::where('language_id', $language_id)
            ->whereIn('question_id', $questions_list)
            ->get();

        return response(['questions' => $questions], 200);
    }
}
