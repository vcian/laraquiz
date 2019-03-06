<?php

namespace App\Imports;

use App\Models\Question\Question;;
use Maatwebsite\Excel\Concerns\ToModel;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class QuestionsImport implements ToModel, WithHeadingRow
{
    private $quiz_id;

    public function __construct($quiz_id) 
    {
        $this->quiz_id = $quiz_id;
    }

    /**
    * @param array $row
    *
    * @return \Illuminate\Database\Eloquent\Model|null
    */
    public function model(array $row)
    {
        $question = Question::create([
            'question' => $row['questions'],
            'quiz_id' => $this->quiz_id
            // 'code_snippet' => $row['code_snippet'],
        ]);

        if ($question->id) {
            $data = [
                [
                    'option' => $row['option_1'],
                    'answer' => $row['option_1'] == $row['answer'] ? 1 : 0
                ],
                [
                    'option' => $row['option_2'],
                    'answer' => $row['option_2'] == $row['answer'] ? 1 : 0
                ],
                [
                    'option' => $row['option_3'],
                    'answer' => $row['option_3'] == $row['answer'] ? 1 : 0
                ],
                [
                    'option' => $row['option_4'],
                    'answer' => $row['option_4'] == $row['answer'] ? 1 : 0
                ],
            ];
            $question->options()->createMany($data);
        }
        return $question;
    }
}
