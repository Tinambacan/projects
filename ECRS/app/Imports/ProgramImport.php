<?php

namespace App\Imports;

use App\Models\Programs;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProgramImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    private $branch;

    public function __construct($branch)
    {
        $this->branch = $branch;
    }
    public function headingRow(): int
    {
        return 1; // Assuming the first row is the header
    }

    public function model(array $row)
    {
        // Clean the keys to remove extra spaces and quotes
        $cleanedRow = [];
        foreach ($row as $key => $value) {
            $cleanedKey = trim(str_replace(['"', ' ', '_'], '', strtolower($key)));
            $cleanedRow[$cleanedKey] = $value;
        }

        $program = new Programs([
            'programCode'    => $cleanedRow['programcode'] ?? null,
            'programTitle'   => $cleanedRow['programtitle'] ?? null,
            'branch'         => $this->branch ?? null,
        ]);

        $program->save();

        return null;
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
