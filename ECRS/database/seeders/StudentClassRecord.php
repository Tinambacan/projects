<?php

namespace Database\Seeders;

use App\Models\Login;
use App\Models\Registration;
use App\Models\Student;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class StudentClassRecord extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {

        $login = Login::create([
            'email' => 'stibenpenecitos@gmail.com',
            'password' => Hash::make('student123'),
        ]);

        Registration::create([
            'Lname' => 'Lim',
            'Fname' => 'Genaro',
            'Mname' => 'Intel',
            'Sname' => '',
            'role' => '3',
            'branch' => '1',
            'schoolIDNo' => '2021-00493-TG-0',
            'loginID' => $login->loginID,
            'adminID' => 1
        ]);

        $login = Login::create([
            'email' => 'nicsfranz69@gmail.com',
            'password' => Hash::make('student123'),
        ]);

        Registration::create([
            'Lname' => 'Mount',
            'Fname' => 'Mary',
            'Mname' => '',
            'Sname' => '',
            'role' => '3',
            'branch' => '2',
            'schoolIDNo' => '2022-00493-TG-0',
            'loginID' => $login->loginID,
            'adminID' => 2
        ]);

        Student::create([
            'studentNo' => '2021-00493-TG-0',
            'studentLname' => 'Lim',
            'studentFname' => 'Genaro',
            'studentMname' => 'Intel',
            'email' => 'stibenpenecitos@gmail.com',
            'mobileNo' => '09161190216',
            'remarks' => 'None',
            'classRecordID' => 2,
        ]);

        Student::create([
            'studentNo' => '2022-00493-TG-0',
            'studentLname' => 'Mount',
            'studentFname' => 'Mary',
            'studentMname' => '',
            'email' => 'nicsfranz69@gmail.com',
            'mobileNo' => '09161190216',
            'remarks' => 'None',
            'classRecordID' => 2,
        ]);
    }
}
