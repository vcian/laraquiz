<?php

namespace App\Models\Quiz;

use App\Models\User;
use Illuminate\Database\Eloquent\Relations\Pivot;

class UserQuizResult extends Pivot
{
    protected $table = "user_quiz_results";

    protected $casts = [
        'total_selected_options' => 'array'
    ];

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['user_id', 'quiz_id', 'total_selected_options', 'total_attempted', 'total_skipped', 'total_wrong', 'total_right'];

    /**
     * Relationship with user table
     *
     * @return \Illuminate\Database\Eloquent\Relations\BelongsTo
     */
    public function user()
    {
      return $this->belongsTo(User::class,'user_id');
    }
}
