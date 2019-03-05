<?php

namespace App\Models\Quiz;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Builder;
use App\Models\Question\Question;
use Illuminate\Support\Str;
use Carbon\Carbon;
use Auth;

class Quiz extends Model
{

    protected $table = 'quizzes';

    /**
     * The attributes that are mass assignable.
     *
     * @var array
     */
    protected $fillable = ['quiz_name', 'admin_id', 'slug', 'time_limit', 'start_time', 'end_time', 'status'];

    // protected $guarded = ['id'];

    /**
     * Set the proper slug attribute.
     *
     * @param string $value
     */
    public function setSlugAttribute($value)
    {
        if (static::withoutGlobalScopes()->whereSlug($slug = Str::slug($value))->exists()) {
            $slug = $this->incrementSlug($slug);
        }
        $this->attributes['slug'] = $slug;
    }

    /**
     * Set the proper time limit attribute.
     *
     * @param string $value
     */
    public function setTimeLimitAttribute($value)
    {
        $this->attributes['time_limit'] = Carbon::createFromFormat('i:s', $value)->format('H:i:s');
    }

    /**
     * get the proper time limit attribute.
     *
     * @param string $value
     */
    public function getTimeLimitAttribute($value)
    {
        return Carbon::createFromFormat('H:i:s', $value)->format('i:s');
    }

    /**
     * Set the proper start time attribute.
     *
     * @param string $value
     */
    public function setStartTimeAttribute($value)
    {
        $this->attributes['start_time'] = Carbon::createFromFormat('Y-m-d H:i', $value)->format('Y-m-d H:i:s');
    }

    /**
     * Set the proper time limit attribute.
     *
     * @param string $value
     */
    public function setEndTimeAttribute($value)
    {
        $this->attributes['end_time'] = Carbon::createFromFormat('Y-m-d H:i', $value)->format('Y-m-d H:i:s');
    }

    /**
     * Increment slug
     *
     * @param   string $slug
     * @return  string
     **/
    public function incrementSlug($slug)
    {
        // get the slug of the latest created quiz
        $max = static::withoutGlobalScopes()->whereQuizName($this->quiz_name)->latest('id')->skip(1)->value('slug');

        if (is_numeric($max[-1])) {
            return pred_replace_callback('/(\d+)$/', function ($mathces) {
                return $mathces[1] + 1;
            }, $max);
        }

        return "{$slug}-2";
    }

    /**
     * Get the questions for the quiz.
     */
    public function questions()
    {
        return $this->hasMany(Question::class, 'quiz_id');
    }

    /**
     * The "booting" method of the model.
     *
     * @return void
     */
    protected static function boot()
    {
        parent::boot();

        // global scope to get only the quiz whose status is active i.e. '1'
        // static::addGlobalScope('status', function (Builder $builder) {
        //     $builder->where('status', 1);
        // });

        // global scope to get only the quiz of the current user
        static::addGlobalScope('admin', function (Builder $builder) {
            $builder->where('admin_id', Auth::id());
        });

        // this will be called after 'create' method is called of the model
        static::created(function ($quiz) {
            $quiz->update(['slug' => $quiz->quiz_name]);
        });

    }
}
