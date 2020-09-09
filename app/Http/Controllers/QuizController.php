<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateQuiz;
use App\Http\Requests\JoinQuiz;
use App\Http\Requests\JoinBulkQuiz;
use App\Http\Requests\SubmitQuiz;
use App\Http\Requests\HostQuiz;
use App\Http\Requests\QuizDetail;

use App\QuestionTranslation;
use App\Quiz;
use App\QuizInfo;
use App\QuizAnswer;
use App\QuizRanking;
use App\Repositories\QuizRepositoryInterface;

use Illuminate\Http\Request;

use Carbon\Carbon;

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
            'expired_at' => $request->expired_at,
            'notify' => 15,
            'time' => 10
        ]);

        $quiz = $this->quizRepositoryInterface->generateQuiz($quizInfo->id, $request->private);

        return response(['quiz' => $quiz], 200);
    }

    public function generate(GenerateQuiz $request)
    {
        $quiz = $this->quizRepositoryInterface->generateQuiz($request->quiz_info_id, true);

        return response(['quiz' => $quiz], 200);
    }

    public function join(JoinQuiz $request)
    {
        $this->quizRepositoryInterface->joinQuiz($request->quiz_id, auth()->id());

        return response(['status' => "done"], 200);
    }

    public function joinBulk(JoinBulkQuiz $request)
    {
        $this->quizRepositoryInterface->joinBulkQuiz($request->quiz_id, $request->total_participants);

        return response(['status' => "done"], 200);
    }

    public function submit(SubmitQuiz $request)
    {
        $this->quizRepositoryInterface->submitQuiz($request->quiz_id, $request->meta);

        return response(['status' => "done"], 200);
    }

    public function getQuizzes(Request $request)
    {
        $quizzes = Quiz::with('host', 'participants', 'quiz_infos', 'rankings')
            ->where('expired_at', '>=', now()->startOfDay())
            ->orWhere('host_id', auth()->id())
            ->orWhereHas('participants', function ($query) {
                return $query->where('user_id', auth()->id());
            })
            ->orderBy('expired_at', 'asc')
            ->get();

        return response(['quizzes' => $quizzes], 200);
    }

    public function getQuizById(QuizDetail $request)
    {
        $quiz = $this->quizRepositoryInterface->getQuizById($request->quiz_id);

        return response(['quiz' => $quiz], 200);
    }

    public function getQuizWinners(QuizDetail $request)
    {
        $winners = QuizRanking::with('quiz', 'user')
            ->where('quiz_id', $request->quiz_id)
            ->orderBy('rank', 'asc')
            ->get();

        return response(['winners' => $winners], 200);
    }

    public function getQuizAnswers(QuizDetail $request)
    {
        $answers = QuizAnswer::with('question')
            ->where(['quiz_id' => $request->quiz_id, 'user_id' => auth()->id()])
            ->get();

        return response(['answers' => $answers], 200);
    }

    public function getAllQuestions(QuizDetail $request)
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

    public function getAnswerableQuestions(QuizDetail $request)
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
