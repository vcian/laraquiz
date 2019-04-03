<?php 

namespace App\Repositories;

use App\Models\Quiz\Quiz;
use App\Models\Quiz\UserQuizResult;
use App\Models\Question\Question;
use App\Models\Question\QuestionOption;
use App\Imports\QuestionsImport;
use App\Models\User;
use Carbon\Carbon;
use DB, Excel, Auth;

class QuizRepository {

    private $model;

    /**
     * Create a new repository instance.
     *
     * @return void
     */
    public function __construct(Quiz $quiz)
    {
        $this->model = $quiz;
    }

    /**
     * @return bool
     */
    public function index() {
        try {
            return $this->model->admin()->get();
        } catch (\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $input
     * @return bool
     */
    public function create($input)
    {
        try {
            DB::beginTransaction();

            $input['admin_id'] = \Auth::id();
            $quiz = $this->model->create($input);

            if ($quiz) {

                if (isset($input['import_questions'])) {
                    Excel::import(new QuestionsImport($quiz->id), $input['import_questions']);

                } else if (count($input['questions'])) {

                    foreach ($input['questions'] as $key => $question) {
                        $question['quiz_id'] = $quiz->id;

                        $createdQuestion = Question::create($question);

                        if( $createdQuestion ) {
                            foreach ($question['options'] as $qkey => $option) {
                                $option['answer'] = (isset($question['answer']) && $question['answer'] == $qkey);
                                $option['question_id'] = $createdQuestion->id;

                                QuestionOption::create($option);
                            }
                        }
                    }
                }
            }

            DB::commit();
            return true;

        } catch (\Exception $ex) {

            DB::rollback();

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function find($id) {
        try {
            $quiz = $this->model->with('questions.options')->whereId($id)->first();

            if ($quiz) {
                return $quiz->toArray();
            }

            return false;

        } catch (\Exception $ex) {

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $slug
     * @return bool
     */
    public function findBySlug($slug) {
        try {
            $quiz = $this->model->with('questions.options')->whereSlug($slug)->first();

            if ($quiz) {
                return $quiz;
            }

            return false;

        } catch (\Exception $ex) {

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $input
     * @param $id
     * @return bool
     */
    public function update($input, $id) {
        try {
            DB::beginTransaction();

            $quiz = $this->model->admin()->find($id);

            if ($quiz->update(array_only($input, ['quiz_name', 'time_limit', 'start_time', 'end_time', 'status']))) {

                if (count($input['questions'])) {
                    $questionIds = $optionIds = [];
                    
                    foreach ($input['questions'] as $key => $question) {
                        
                        if (isset($question['id'])) {

                            $questionIds[] = $question['id'];

                            if(Question::where('id', $question['id'])->update(array_only($question, ['question','code_snippet']))) {

                                foreach ($question['options'] as $qkey => $option) {
                                    $optionIds[] = $option['id'];
                                    $option['answer'] = (isset($question['answer']) && $question['answer'] == $qkey);

                                    QuestionOption::where('id', $option['id'])->where('question_id', $question['id'])->update($option);
                                }
                            }

                        } else {

                            $question['quiz_id'] = $id;
                            $q = Question::create($question);
                            $questionIds[] = $q->id;

                            if( $q ) {
                                foreach ($question['options'] as $qkey => $option) {
                                    $option['answer'] = (isset($question['answer']) && $question['answer'] == $qkey);
                                    $option['question_id'] = $q->id;

                                    $qOption = QuestionOption::create($option);
                                    $optionIds[] = $qOption->id;
                                }
                            }
                        }
                    }
                }

                // delete extra rows from Question table and its options which are removed from the form
                QuestionOption::whereIn('question_id', $questionIds)->whereNotIn('id', $optionIds)->delete();
                Question::where('quiz_id', $id)->whereNotIn('id', $questionIds)->delete();
            }

            DB::commit();
            return true;

        } catch (\Exception $ex) {

            DB::rollback();

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $id
     * @return bool
     */
    public function delete($id) {
        try {
            DB::beginTransaction();

            // Code to delete related models
            $questionIds = Question::where('quiz_id', $id)->pluck('id')->toArray();

            QuestionOption::whereIn('question_id', $questionIds)->delete();
            Question::where('quiz_id', $id)->delete();

            $this->model->admin()->whereId($id)->delete();

            DB::commit();
            return true;

        } catch (\Exception $ex) {

            DB::rollback();

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $slug
     * @return bool
     */
    public function quizExists($slug)
    {
        try {

            return $this->model
                ->whereSlug($slug)
                ->exists();

        } catch (\Exception $ex) {

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $slug
     * @return bool
     */
    public function checkStartTime($slug)
    {
        try {

            return $this->model
                ->where('start_time', '<=', Carbon::now())->where('end_time', '>=', Carbon::now())
                ->exists();

        } catch (\Exception $ex) {

            \Log::error($ex);
            return false;
        }
    }

    /**
     * @param $input
     * @param $slug
     * @return bool
     */
    public function quizStore($input, $slug)
    {
        DB::beginTransaction();

        try {
            $totalWrong = $totalRight = 0;

            if (isset($input['options'])) {

                foreach ($input['options'] as $key => $value) {
                    $result = QuestionOption::where(['answer' => 1, 'question_id' => $key])->first();

                    if ($result && $result->id == $value) {
                        $totalRight++;
                    } else {
                        $totalWrong++;
                    }
                }
            }

            $totalAttempted = $totalRight + $totalWrong;
            $totalSkipped = $input['total_q'] - $totalAttempted;
            
            $userQuizResult = array();
            $user = Auth::guard('web')->user();
            
            $userQuizResult['total_attempted'] = $totalAttempted;
            $userQuizResult['total_skipped'] = $totalSkipped;
            $userQuizResult['total_wrong'] = $totalWrong ? $totalWrong : 0;
            $userQuizResult['total_right'] = $totalRight ? $totalRight : 0;

            $quiz = $this->model->whereSlug($slug)->first();
            $result = $quiz->users()->syncWithoutDetaching([$user->id => $userQuizResult]);
            $user->update(['end_time' => Now()]); // Update the user quiz end time

            if (count($result['attached'])) {

                DB::commit();
                return true;

            } else {

                DB::rollBack();
                return false;
            }

        } catch (\Exception $ex) {

            \Log::error($ex);

            DB::rollBack();
            return false;
        }
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getWinnerList($slug)
    {
        $winners = UserQuizResult::select([
                \DB::raw('TIMEDIFF(users.end_time, users.start_time) as diff'), 
                'users.*', 
                'users.end_time as stop_time',
                'quizzes.slug'
            ])
            ->join('users','user_quiz_results.user_id','=','users.id')
            ->join('quizzes', 'quizzes.id', '=', 'user_quiz_results.quiz_id')
            ->where('quizzes.slug', $slug)
            ->whereNotNull('users.end_time')
            ->orderBy('user_quiz_results.total_right', 'desc')
            ->orderBy('diff', 'asc')
            ->take(3)
            ->get();

        return $winners;
    }

    /**
     * @param $slug
     * @return mixed
     */
    public function getPlayersList($slug)
    {
        $quiz = $this->findBySlug($slug);

        $players = User::where('quiz_id', $quiz->id)->orderByDesc('created_at')->get();

        return $players;
    }
}