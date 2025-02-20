<?php

namespace App\Imports;

use App\Models\Courses;
use App\Models\Registration;
use App\Models\Student;
use App\Models\User;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rule;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;



class ProfImport implements ToModel, SkipsEmptyRows, WithHeadingRow, WithValidation
{
    public $users;

    public  function __construct()
    {
        $this->users = User::select('login_ID', 'email')->get();
    }

    /**
     * @param array $row
     *
     * @return \Illuminate\Database\Eloquent\Model|null
     */
    public function model(array $row)
    {


        $existingUser = User::where('email', $row['email'])->first();
        if ($existingUser) {
            throw new \Exception("Email already exists: " . $row['email']);
        }

        $user = new User([
            "email"         => $row['email'],
            "password"      => Hash::make('password'),
        ]);

        $user->save();

        $student = new Registration([
            'login_ID'       => $user->login_ID,
            'last_name'      => $row['last_name'],
            'first_name'     => $row['first_name'],
            'middle_name'    => $row['middle_name'],
            'role'           => 2,
            'isActive'       => 0,

        ]);
        $student->save();

        return [$user, $student];
    }

    public function rules(): array
    {
        return [
            'email' => [
                'required',
                'string',  
            ],

            // 'student_number' => [
            //     'required',
            //     function ($attribute, $value, $fail) {
            //         if (!preg_match('/^\d{4}-\d{5}-TG-\d$/', $value)) {
            //             throw new \Exception($value . ' must be in the format "0000-00000-TG-0".');
            //         }
            //     },
            // ],
        ];
    }
    // public function chunckSize(): int
    // {
    //     return 5000;
    // }
}
