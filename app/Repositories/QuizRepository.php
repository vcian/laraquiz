<?php 

namespace App\Repositories;

use App\Models\Quiz\Quiz;
use App\Models\Question\Question;
use App\Models\Question\QuestionOption;
// use Illuminate\Support\Str;
use App\Imports\QuestionsImport;
use DB;
use Excel;

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

    public function index() {
        try {
            return $this->model->get();
        } catch (\Exception $ex) {
            \Log::error($ex);
            return false;
        }
    }

    public function create($input) {
        try {
            DB::beginTransaction();
            // $input['slug'] = Str::slug($input['quiz_name'], '-');
            $input['admin_id'] = \Auth::id();
            $quiz = $this->model->create($input);
            
            if ($quiz) {
                // dd($input['import_questions']);
                if (isset($input['import_questions'])) {
                    Excel::import(new QuestionsImport($quiz->id), $input['import_questions']);
                }
                // add questions
                else if (count($input['questions'])) {
                    foreach ($input['questions'] as $key => $question) {
                        $question['quiz_id'] = $quiz->id;

                        $createdQuestion = Question::create($question);

                        // add question options
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

    public function update($input, $id) {
        try {
            DB::beginTransaction();
            // DB::enableQueryLog();
            $quiz = $this->model->find($id);
            if ($quiz->update(array_only($input, ['quiz_name', 'time_limit', 'start_time', 'end_time', 'status']))) {
                // $query = DB::getQueryLog();
                // dd(end($query));
                if (count($input['questions'])) {
                    $questionIds = $optionIds = [];
                    
                    foreach ($input['questions'] as $key => $question) {
                        
                        if (isset($question['id'])) {
                            // update quiz question
                            $questionIds[] = $question['id'];
                            if(Question::where('id', $question['id'])->update(array_only($question, ['question']))) {

                                foreach ($question['options'] as $qkey => $option) {
                                    $optionIds[] = $option['id'];
                                    $option['answer'] = (isset($question['answer']) && $question['answer'] == $qkey);

                                    QuestionOption::where('id', $option['id'])->where('question_id', $question['id'])->update($option);
                                }
                            }
                        } else {
                            // add new question
                            $question['quiz_id'] = $id;
                            $q = Question::create($question);
                            $questionIds[] = $q->id;

                            // add question options
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

    public function delete($id) {
        try {
            DB::beginTransaction();

            // Code to delete related models
            $questionIds = Question::where('quiz_id', $id)->pluck('id')->toArray();
            QuestionOption::whereIn('question_id', $questionIds)->delete();
            Question::where('quiz_id', $id)->delete();
            $this->model->whereId($id)->delete();
            DB::commit();
            return true;
        } catch (\Exception $ex) {
            DB::rollback();
            \Log::error($ex);
            return false;
        }
    }
}