<?php

namespace App\Http\Controllers;

use App\Http\Requests\GenerateQuiz;
use App\Http\Requests\JoinQuiz;
use App\Http\Requests\JoinBulkQuiz;
use App\Http\Requests\SubmitQuiz;
use App\Http\Requests\HostQuiz;
use App\Http\Requests\QuizDetail;
use App\Http\Requests\QuizQuestion;
use App\QuestionTranslation;
use App\Quiz;
use App\QuizInfo;
use App\QuizAnswer;
use App\QuizParticipant;
use App\QuizRanking;
use App\Repositories\QuizRepositoryInterface;
use App\User;
use Illuminate\Http\Request;

use Error;
use Illuminate\Support\Facades\DB;

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
            'all_questions_count' => 20,
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
        $this->quizRepositoryInterface->joinQuiz($request->quiz_id, $request->user_id);

        return response(['status' => "done"], 200);
    }

    public function joinBulk(JoinBulkQuiz $request)
    {
        $this->quizRepositoryInterface->joinBulkQuiz($request->quiz_id, $request->total_participants);

        return response(['status' => "done"], 200);
    }

    public function submitQuiz(SubmitQuiz $request)
    {
        $this->quizRepositoryInterface->submitQuiz($request->quiz_id, $request->meta);

        return response(['status' => "done"], 200);
    }

    public function getActiveQuizzes(Request $request)
    {
        $quizQuery = Quiz::query()
            ->with('host', 'participants', 'quiz_infos', 'rankings')
            ->withCount('host', 'participants', 'quiz_infos', 'rankings')
            ->where('private', false)
            ->where(function ($query) {
                return $query->where('status', 'pending')
                    ->orWhere('status', 'started')
                    ->orWhere('status', 'full');
            })
            ->where(function ($query) use ($request) {
                if ($request->segment !== "all") {
                    if ($request->segment === "Hosted Quiz") {
                        return $query->where('host_id', auth()->id());
                    }

                    if ($request->segment === "Joined Quiz") {
                        return $query->whereHas('participants', function ($query) {
                            return $query->where('user_id', auth()->id());
                        });
                    }
                }
            })
            ->where(function ($query) use ($request) {
                if ($request->timing !== "all") {
                    return $query->where('expired_at', $request->timing);
                }

                return $query->where('expired_at', '>=', now()->startOfDay());
            });

        if ($request->key === "latest") {
            $quizQuery = $quizQuery->orderBy('expired_at', $request->direction);
        }

        if ($request->key === "Entry Fee") {
            $quizQuery = $quizQuery->orderBy(
                QuizInfo::select('entry_fee')->whereColumn('id', 'quizzes.quiz_info_id'),
                $request->direction
            );
        }

        if ($request->key === "Participants") {
            $quizQuery = $quizQuery->orderBy('participants_count', $request->direction);
        }

        if ($request->key === "Prize Amount") {
            $quizQuery = $quizQuery->orderBy(
                QuizInfo::select(DB::raw('(entry_fee * total_participants) as prize_amount'))->whereColumn('id', 'quizzes.quiz_info_id'),
                $request->direction
            );
        }

        $quizzes = $quizQuery->get();

        return response(['quizzes' => $quizzes], 200);
    }

    public function getUserQuizzes(Request $request)
    {
        $segments = ['hosted', 'joined', 'won', 'lost', 'cancelled', 'missed'];

        foreach ($segments as $segment) {
            $quizzes[$segment] = $this->getSegmentWiseQuiz($segment);
        }

        return response(['quizzes' => $quizzes], 200);
    }

    public function getSegmentWiseQuiz($segment)
    {
        $userQuizzes = Quiz::with('host', 'participants', 'quiz_infos', 'rankings', 'answerable_questions')
            ->where(function ($query) use ($segment) {
                if ($segment === "hosted") {
                    return $query->where('host_id', auth()->id());
                }

                if ($segment === "joined") {
                    return $query
                        ->whereHas('participants', function ($query) {
                            return $query->where('user_id', auth()->id());
                        });
                }

                if ($segment === "won") {
                    return $query
                        ->whereHas('participants', function ($query) {
                            return $query->where('user_id', auth()->id())->where('status', 'finished');
                        })
                        ->whereHas('rankings', function ($query) {
                            return $query->where('prize', '>', 0)->where('user_id', auth()->id());
                        });
                }

                if ($segment === "lost") {
                    return $query
                        ->whereHas('participants', function ($query) {
                            return $query->where('user_id', auth()->id())->where('status', 'finished');
                        })
                        ->whereHas('rankings', function ($query) {
                            return $query->where('prize', 0)->where('user_id', auth()->id());
                        });
                }

                if ($segment === "missed") {
                    return $query
                        ->whereHas('participants', function ($query) {
                            return $query->where('user_id', auth()->id())->where('status', 'missed');
                        });
                }

                if ($segment === "cancelled") {
                    return $query
                        ->whereHas('participants', function ($query) {
                            return $query->where('user_id', auth()->id())->where('status', 'suspended');
                        });
                }
            })
            ->orderBy('expired_at', 'desc')
            ->get();

        return $userQuizzes;
    }

    public function searchQuizByTitle(Request $request)
    {
        $search = Quiz::where('title', $request->title)->first();

        if ($search) {
            $quiz = $this->quizRepositoryInterface->getQuizById($search->id);

            return response(['quiz' => $quiz], 200);
        }

        return response(['quiz' => null], 200);
    }

    public function getQuizById(QuizDetail $request)
    {
        $quiz = $this->quizRepositoryInterface->getQuizById($request->quiz_id);

        return response(['quiz' => $quiz], 200);
    }

    public function startQuiz(QuizDetail $request)
    {
        $user = User::with('country')->find(auth()->id());

        $quiz = $this->quizRepositoryInterface->getQuizById($request->quiz_id);

        $this->quizRepositoryInterface->startQuiz($quiz, $user);

        return response(['success' => true], 200);
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

    public function getQuizQuestions(QuizQuestion $request)
    {
        $quiz_questions = DB::table('quiz_questions')->where('quiz_id', $request->quiz_id)->get();

        $questions_list = $quiz_questions
            ->pluck('question_id')
            ->toArray();

        $questions = QuestionTranslation::where('language_id', $request->language_id)
            ->whereIn('question_id', $questions_list)
            ->get()
            ->map(function ($question) use ($quiz_questions) {
                $question['quiz_question'] = $quiz_questions->where("question_id", $question->question_id)->first();

                return $question;
            });

        return response(['questions' => $questions], 200);
    }
}
