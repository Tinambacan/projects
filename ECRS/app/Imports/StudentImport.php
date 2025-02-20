<?php

namespace App\Imports;

use App\Mail\BatchStudentAccountCredentials;
use App\Mail\StudentAccountCredentials;
use App\Models\Login;
use App\Models\Registration;
use App\Models\Student;
use App\Notifications\BatchFacultySendStudentCredentials;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Maatwebsite\Excel\Concerns\SkipsEmptyRows;
use Maatwebsite\Excel\Concerns\ToCollection;
use Maatwebsite\Excel\Concerns\ToModel;
use Maatwebsite\Excel\Concerns\WithHeadingRow;
use Maatwebsite\Excel\Concerns\WithValidation;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Str;
use Maatwebsite\Excel\Validators\ValidationException;

use function Ramsey\Uuid\v4;

class StudentImport implements ToModel, SkipsEmptyRows, WithHeadingRow
{
    protected $classRecordID;
    protected $facultyRegistration;

    public function __construct($classRecordID, $facultyRegistration)
    {
        $this->classRecordID = $classRecordID;
        $this->facultyRegistration = $facultyRegistration;
    }

    public function headingRow(): int
    {
        return 1;
    }

    public function model(array $row)
    {
        // Clean up the row keys
        $cleanedRow = [];
        foreach ($row as $key => $value) {
            $cleanedKey = trim(str_replace(['"', ' ', '_'], '', strtolower($key)));
            $cleanedRow[$cleanedKey] = $value;
        }

        DB::beginTransaction();

        try {
            $existingStudents = Student::where('classRecordID', $this->classRecordID)->get();
            $existingStudentNumbers = $existingStudents->pluck('studentNo')->toArray();

            // Check for duplicate student numbers in the class record
            if (in_array($cleanedRow['studentnumber'], $existingStudentNumbers)) {
                throw new \Exception("Student number already exists in the class record: " . $cleanedRow['studentnumber']);
            }

            // Check if the student number already exists in registration_tbl
            $registration = Registration::where('schoolIDNo', $cleanedRow['studentnumber'])->first();
            $login = null;

            if (!$registration) {
                $login = Login::where('email', $cleanedRow['email'])->first();
                if (!$login) {
                    $plainPassword = Str::random(8); 
                    $login = Login::create([
                        'email' => $cleanedRow['email'],
                        'password' => bcrypt($plainPassword),
                    ]);

                    Registration::create([
                        'Lname' => $cleanedRow['lastname'] ?? null,
                        'Fname' => $cleanedRow['firstname'] ?? null,
                        'Mname' => $cleanedRow['middlename'] ?? null,
                        'Sname' => $cleanedRow['suffix'] ?? null,
                        'role' => 3,
                        'schoolIDNo' => $cleanedRow['studentnumber'],
                        'branch' => $this->facultyRegistration->branch,
                        'loginID' => $login->loginID,
                        'adminID' => $this->facultyRegistration->adminID,
                        'isActive' => 0,
                        'isSentCredentials' => 1, 
                    ]);


                    Mail::to($cleanedRow['email'])->send(new BatchStudentAccountCredentials(
                        $plainPassword,
                        $cleanedRow['studentnumber'],
                        $cleanedRow['firstname'],
                        $cleanedRow['lastname'],
                        $cleanedRow['middlename'],
                        $cleanedRow['email']
                    ));

                     // $login->notify(new BatchFacultySendStudentCredentials(
                    //     $plainPassword,
                    //     $cleanedRow['studentnumber'],
                    //     $cleanedRow['firstname'],
                    //     $cleanedRow['lastname'],
                    //     $cleanedRow['middlename'],
                    //     $cleanedRow['email']
                    // ));
                }
            }

            // Create the student record
            $student = new Student([
                'studentNo'      => $cleanedRow['studentnumber'] ?? null,
                'studentFname'   => $cleanedRow['firstname'] ?? null,
                'studentLname'   => $cleanedRow['lastname'] ?? null,
                'studentMname'   => $cleanedRow['middlename'] ?? null,
                'email'          => $cleanedRow['email'] ?? null,
                'mobileNo'       => $cleanedRow['mobilenumber'] ?? null,
                'remarks'        => $cleanedRow['remarks'] ?? null,
                'classRecordID'  => $this->classRecordID,
            ]);

            if ($student->studentNo) {
                $student->save();
                DB::commit();
                return $student;
            }

            DB::rollBack();
        } catch (\Exception $e) {
            DB::rollBack();
            throw $e;
        }

        return null;
    }

    public function rules(): array
    {
        return [
            'studentnumber' => [
                'required',
                'string',
            ],
        ];
    }
}
