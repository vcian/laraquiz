<?php

namespace App\Http\Controllers\FrontEnd;

use App\Events\QuizDashboardEvent;
use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;
use App\Repositories\QuizRepository;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Log;
use App\Events\QuizWinnerEvent;
use Illuminate\Support\Facades\Validator;

class QuizController extends Controller
{
    protected $repo;

    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct(QuizRepository $quizRepo)
    {
        $this->middleware('auth:web')->only(['quizStart', 'thankYou']);
        $this->middleware('guest:web')->only(['index', 'registerUser']);
        $this->repo = $quizRepo;

        view()->share('title', 'Quiz');
    }

    /**
     * Display a quiz access page.
     *
     * @return \Illuminate\Http\Response
     */
    public function index($slug)
    {
        if ($this->repo->quizExists($slug)) {
            if ($this->repo->checkStartTime($slug)) { // check quiz time is started to play
                view()->share('slug', $slug);
                return view('frontEnd.quiz_login');
            }
            return view('frontEnd.not_yet_started');
        }

        return abort('404');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request $request
     * @param String $slug
     * @return \Illuminate\Http\Response
     */
    public function registerUser(Request $request, $slug)
    {
        $quiz = Quiz::whereSlug($slug)->first();

        $request->validate([
            'full_name' => 'required|max:255',
            'nick_name' => 'required|unique:users,nick_name,NULL,id,quiz_id,' . $quiz->id . '|max:255|regex:/^[ A-Za-z0-9_@#&$-]*$/',
        ],[
            'nick_name.regex' => 'Allow alphanumeric with special characters like _,@,#,&,$,-'
        ]);

        $input = $request->all();
        $input['quiz_id'] = $quiz->id;
        $input['start_time'] = Now();

        $userRepo = \App::make('App\\Repositories\\FrontEnd\\UserRepository');
        $user = $userRepo->create($input);

        if ($user) {
            \Auth::guard('web')->login($user);
            return redirect(route("quiz.play", [$slug]));
        };

        return back()
            ->with('error', __('There are some issue found. Try Again!'));
    }

    /**
     * List the questions of the quiz
     *
     * @param $slug
     * @return \Illuminate\Contracts\View\Factory|\Illuminate\View\View
     */
    public function quizStart($slug)
    {
        $quiz = $this->repo->findBySlug($slug);

        view()->share('title', $quiz->quiz_name);
        view()->share('quiz', $quiz);

        broadcast(new QuizDashboardEvent($slug));

        return view('frontEnd.quiz');
    }

    /**
     * Submit the quiz and store the records
     *
     * @param  \Illuminate\Http\Request $request
     * @param String $slug
     * @return \Illuminate\Http\Response
     */
    public function quizStore(Request $request, $slug)
    {
        $inputs = $request->all();
        $result = $this->repo->quizStore($inputs, $slug);

        if ($result) {

            broadcast(new QuizDashboardEvent($slug));

            broadcast(new QuizWinnerEvent($slug));

            return redirect()
                ->route('quiz.thankYou', $slug)
                ->with('success', 'Thank you for attempt quiz!');
        } else {
            \Auth::guard('web')->logout();
            return redirect()
                ->route('quiz.login', $slug)
                ->with('error', 'Sorry, There are some issue found. Try Again!');
        }
    }

    /**
     * Show Thank you page after quiz submission.
     *
     * @param  $slug
     * @return bool
     */
    public function thankYou($slug)
    {
        try {
            \Auth::guard('web')->logout();

            return view('frontEnd.thankyou');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }

    public function dashboard($slug)
    {
        try {
            return view('frontEnd.quiz_dashboard');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }

    public function winners($slug)
    {
        try {
            return view('frontEnd.winner');
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }

    public function fetchWinners($slug)
    {
        try {
            return $this->repo->getWinnerList($slug);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }

    public function fetchPlayers($slug)
    {
        try {
            return $this->repo->getPlayersList($slug);
        } catch (\Exception $ex) {
            Log::error($ex->getMessage());
            return $ex->getMessage();
        }
    }

}
