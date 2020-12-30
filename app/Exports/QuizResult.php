<?php

namespace App\Exports;

use App\Models\Quiz\UserQuizResult;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromView;

class QuizResult implements FromView
{
    protected $quizId;

    public function __construct($quizId)
    {
        $this->quizId = $quizId;
    }

    public function view(): View
    {
        return view('exports.quiz-results', [
            'results' => UserQuizResult::with('user')->where('quiz_id',$this->quizId)->get()
        ]);
    }
}
