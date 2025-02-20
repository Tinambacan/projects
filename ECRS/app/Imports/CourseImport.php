<?php

namespace App\Imports;

use App\Models\Courses;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class CourseImport implements ToModel, SkipsEmptyRows, WithHeadingRow 
{
    private $programID;

    public function __construct($programID)
    {
        $this->programID = $programID;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        // Clean the keys to remove extra spaces and quotes
        $cleanedRow = [];
        foreach ($row as $key => $value) {
            $cleanedKey = trim(str_replace(['"', ' ', '_'], '', strtolower($key)));
            $cleanedRow[$cleanedKey] = $value;
        }

        return new Courses([
            'courseCode'    => $cleanedRow['coursecode'] ?? null,
            'courseTitle'   => $cleanedRow['coursetitle'] ?? null,
            'programID'     => $this->programID,
        ]);
    }
    
    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
            ],
        ];
    }
}

