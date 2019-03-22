<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Model;
use App\Models\Quiz\Quiz;

class Question extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quiz_id', 'question','code_snippet'];

    /**
     * Get the quiz for the question.
     */
    public function quiz()
    {
        return $this->belongsTo(Quiz::class, 'quiz_id');
    }

    /**
     * Get the options for the question.
     */
    public function options()
    {
        return $this->hasMany(QuestionOption::class, 'question_id');
    }
}
