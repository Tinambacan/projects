<?php

namespace Database\Seeders;

use App\Models\Login;
use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class FacultySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $login = Login::create([
            'email' => 'faculty1@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        Registration::create([
            'Lname' => 'doe',
            'Fname' => 'john',
            'Mname' => '',
            'Sname' => '',
            'salutation' => 'Prof.',
            'role' => '1',
            'branch' => '1',
            'schoolIDNo' => '123',
            'loginID' => $login->loginID,
            'adminID' => 1
        ]);

        $login2 = Login::create([
            'email' => 'faculty2@gmail.com',
            'password' => Hash::make('password456'),
        ]);

        Registration::create([
            'Lname' => 'Smith',
            'Fname' => 'Jane',
            'Mname' => '',
            'Sname' => '',
            'salutation' => 'Prof.',
            'role' => '1',
            'branch' => '2',
            'schoolIDNo' => '456',
            'loginID' => $login2->loginID,
            'adminID' => 2
        ]);
    }
}
