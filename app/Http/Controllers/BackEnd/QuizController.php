<?php

namespace App\Http\Controllers\BackEnd;

use App\Exports\QuizResult;
use App\Models\Quiz\Quiz;
use Illuminate\Http\Request;
use App\Repositories\QuizRepository;
use App\Http\Controllers\Controller;
use App\Models\Quiz\UserQuizResult;
use Exception;
use Illuminate\Support\Facades\Log;
use Excel;

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
        $this->middleware('auth:admin');

        $this->repo = $quizRepo;

        view()->share('title', 'Quiz');
    }

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $quizzes = $this->repo->index();

        view()->share('quizzes', $quizzes);

        return view('backEnd.quiz.index');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('backEnd.quiz.create');
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'quiz_name' => 'required|unique:quizzes,quiz_name,NULL,id,admin_id,' . \Auth::id() . '|max:255',
            'time_limit' => 'required|date_format:i:s',
            'start_time' => 'required',
            'end_time' => 'required',
            'import_questions' => 'mimetypes:application/vnd.ms-excel,application/vnd.oasis.opendocument.text,application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'questions.*.question' => 'required:import_questions,NULL',
            'questions.*.options.*.option' => 'required:import_questions,NULL',
        ], [
            'time_limit.required' => 'The quiz total time field is required.',
            'time_limit.date_format' => 'The quiz time should match the format MM:SS.',
            'questions.*.question.required' => 'The field is required.',
            'questions.*.options.*.option.required' => 'The field is required.',
            'import_questions.mimetypes' => 'Choose only .xlsx, .xls, .odt file'
        ]);

        $inputs = $request->all();
        if ($this->repo->create($inputs)) {
            return redirect()
                ->route('admin.quiz.index')
                ->with('success', __('Quiz created successfully!'));
        } else {
            return back()
                ->withInput()
                ->with('error', __('There are some issue found.'));
        }
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Quiz\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function edit(Quiz $quiz)
    {
        $quiz = $this->repo->find($quiz->id);

        view()->share('quiz', $quiz);

        return view('backEnd.quiz.edit');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $request->validate([
            'quiz_name' => 'required|unique:quizzes,quiz_name,' . $id . ',id,admin_id,' . \Auth::id() . '|max:255',
            'time_limit' => 'required|date_format:i:s',
            'start_time' => 'required',
            'end_time' => 'required',
            'questions.*.question' => 'required',
            'questions.*.options.*.option' => 'required',
        ], [
            'time_limit.required' => 'The quiz total time field is required.',
            'time_limit.date_format' => 'The quiz time should match the format MM:SS.',
            'questions.*.question.required' => 'The field is required.',
            'questions.*.options.*.option.required' => 'The field is required.',
        ]);

        $inputs = $request->all();

        if ($this->repo->update($inputs, $id)) {
            return redirect()
                ->route('admin.quiz.index')
                ->with('success', __('Quiz updated successfully!'));
        } else {
            return back()
                ->withInput()
                ->with('error', __('There are some issue found.'));
        }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Quiz\Quiz  $quiz
     * @return \Illuminate\Http\Response
     */
    public function destroy(Quiz $quiz)
    {
        if ($this->repo->delete($quiz->id)) {
            return redirect()
                ->route('admin.quiz.index')
                ->with('success', __('Quiz deleted successfully!'));
        } else {
            return back()
                ->withInput()
                ->with('error', __('There are some issue found.'));
        }
    }

    public function downloadSample()
    {
        $headers = ['Cache-Control' => 'no-store, no-cache, must-revalidate, post-check=0, pre-check=0'];

        return response()
            ->download(resource_path('files/Laravel Live Quiz sample.xlsx'), 'SampleQuizQuestions.xlsx', $headers);
    }

    public function exportQuizResults($quizId)
    {
        try{
            return Excel::download(new QuizResult($quizId), 'quiz-results.xlsx');
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
    }

    public function quizResult($quizId)
    {
        try{
            $results = UserQuizResult::where('quiz_id',$quizId)->get();
            
            return view('backEnd.quiz.results_index',compact('results'));
        }catch(Exception $ex){
            Log::error($ex->getMessage());
        }
    }
}
