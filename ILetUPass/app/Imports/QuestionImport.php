<?php

namespace App\Imports;

use App\Models\Question;
use App\Models\Answer;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Validation\ValidationException;
use Maatwebsite\Excel\Row;
use Illuminate\Support\Facades\Validator;
use Maatwebsite\Excel\Concerns\OnEachRow;

class QuestionImport implements OnEachRow, WithHeadingRow
{
    protected $subjectID;
    protected $invalidRows = [];
    public function __construct($subjectID)
    {
        $this->subjectID = $subjectID;
    }
    public function onRow(Row $row)
    {
        $rowIndex = $row->getIndex();
    $rowData = $row->toArray();

    $correctAnswerCount = collect([
        $rowData['choice_1_answer'],
        $rowData['choice_2_answer'],
        $rowData['choice_3_answer'],
        $rowData['choice_4_answer'],
    ])->filter(function ($answer) {
        return $answer == '1';
    })->count();

    if ($correctAnswerCount !== 1) {
        $this->invalidRows[] = $rowIndex;

        // Throw an exception to stop the import process for this row
        throw new \Exception("Validation failed for row $rowIndex");
    } else {
            $question = Question::create([
                'question_desc' => $row['question_desc'],
                'question_exp' => $row['question_exp'],
                'level' => $row['level'],
                'subject_ID' => $this->subjectID,
            ]);
    
            // Create answers for this question
            $answers = [
                ['choices_desc' => $row['choice_1_desc'], 'answer' => $row['choice_1_answer']],
                ['choices_desc' => $row['choice_2_desc'], 'answer' => $row['choice_2_answer']],
                ['choices_desc' => $row['choice_3_desc'], 'answer' => $row['choice_3_answer']],
                ['choices_desc' => $row['choice_4_desc'], 'answer' => $row['choice_4_answer']],
            ];
    
            foreach ($answers as $answerData) {
                Answer::create([
                    'choices_desc' => $answerData['choices_desc'],
                    'answer' => $answerData['answer'],
                    'question_ID' => $question->question_ID,
                ]);
            }
        }
    }
    public function getInvalidRows()
    {
        return $this->invalidRows;
    }
    public function rules(): array
    {
        return [
            'question_desc' => 'required',
            'level' => 'required',
            'choice_1_desc' => 'required',
            'choice_1_answer' => 'required|in:0,1',
            'choice_2_desc' => 'required',
            'choice_2_answer' => 'required|in:0,1',
            'choice_3_desc' => 'required',
            'choice_3_answer' => 'required|in:0,1',
            'choice_4_desc' => 'required',
            'choice_4_answer' => 'required|in:0,1',
        ];
    }
}
