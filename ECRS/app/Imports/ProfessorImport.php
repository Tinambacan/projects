<?php

namespace App\Imports;

use App\Models\Login;
use App\Models\Registration;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;

class ProfessorImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    protected $adminLoginID;
    protected $branch;

    public function __construct($adminLoginID, $branch)
    {
        $this->adminLoginID = $adminLoginID;
        $this->branch = $branch;
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

        // Generate a random password
        $generatedPassword = Str::random(8);

        // Insert into login_tbl
        $login = new Login([
            'email'    => $cleanedRow['institutionalemail'] ?? null,
            'password' => Hash::make($generatedPassword),
        ]);
        $login->save();

        // Insert into registration_tbl
        $professor = new Registration([
            'Lname'             => $cleanedRow['lastname'] ?? null,
            'Fname'             => $cleanedRow['firstname'] ?? null,
            'Mname'             => null, // Middle name is not provided in the CSV
            'Sname'             => null, // Suffix name is not provided in the CSV
            'role'              => 1, // Assuming 1 represents a faculty role
            'schoolIDNo'        => $cleanedRow['facultycode'] ?? null,
            'branch'            => $this->branch,
            'isActive'          => 0,
            'isSentCredentials' => 0,
            'adminID'           => $this->adminLoginID,
            'loginID'           => $login->loginID,
        ]);

        $professor->save();

        // Optionally log or send credentials to the professor
        // Log::info("Generated password for {$cleanedRow['institutionalemail']}: $generatedPassword");

        return $professor;
    }


    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',
                'email',
            ],
        ];
    }
}
