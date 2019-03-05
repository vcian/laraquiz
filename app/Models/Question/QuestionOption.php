<?php

namespace App\Models\Question;

use Illuminate\Database\Eloquent\Model;

class QuestionOption extends Model
{
    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['question_id', 'option', 'answer'];

    /**
     * Get the question for the option.
     */
    public function question()
    {
        return $this->belongsTo(Question::class, 'question_id');
    }
}
