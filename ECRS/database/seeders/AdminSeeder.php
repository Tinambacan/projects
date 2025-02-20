<?php

namespace Database\Seeders;

use App\Models\Admin;
use App\Models\Login;
use App\Models\Registration;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $admin1 = Login::create([
            'email' => 'admin1@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        Admin::create([
            'Lname' => 'Doe',
            'Fname' => 'Admin',
            'Mname' => '',
            'Sname' => '',
            'salutation' => 'Mr.',
            'schoolYear' => '2024-2025',
            'semester' => '1',
            'branch' => '1',
            'signature' => '',
            'loginID' => $admin1->loginID
        ]);

        $admin2 = Login::create([
            'email' => 'admin2@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        Admin::create([
            'Lname' => 'Sherly',
            'Fname' => 'Admin',
            'Mname' => '',
            'Sname' => '',
            'salutation' => 'Ms.',
            'schoolYear' => '2024-2025',
            'semester' => '1',
            'branch' => '2',
            'signature' => '',
            'loginID' => $admin2->loginID
        ]);
    }
}
