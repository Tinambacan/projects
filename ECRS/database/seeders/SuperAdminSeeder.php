<?php

namespace Database\Seeders;

use App\Models\Login;
use App\Models\SuperAdmin;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class SuperAdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $superadmin1 = Login::create([
            'email' => 'superadmin1@gmail.com',
            'password' => Hash::make('password123'),
        ]);

        SuperAdmin::create([
            'Lname' => 'ecrs',
            'Fname' => 'pupt',
            'Mname' => '',
            'Sname' => '',
            'salutation' => '',
            'signature' => '',
            'loginID' => $superadmin1->loginID
        ]);
    }
}
