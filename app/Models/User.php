<?php

namespace App\Models;

use Illuminate\Notifications\Notifiable;
use Illuminate\Contracts\Auth\MustVerifyEmail;
use Illuminate\Foundation\Auth\User as Authenticatable;
use App\Models\Quiz\Quiz;
use App\Models\Quiz\UserQuizResult;

class User extends Authenticatable
{
    use Notifiable;

    protected $guard = 'web';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = [
        'quiz_id', 'full_name', 'nick_name', 'start_time', 'end_time'
    ];

    /**
     * The attributes that should be hidden for arrays.
     *
     * @var array
     */
    protected $hidden = [
        'remember_token',
    ];

    /**
     * User has played many Quiz
     * 
     */
    public function quizzes()
    {
        return $this->belongsToMany(Quiz::class, 'user_quiz_results', 'user_id', 'quiz_id')->withTimestamps();
    }
}
