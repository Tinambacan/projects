<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class AdminSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //
        $user =User::create([
            'email' => 'admin.letupass@gmail.com',
            'password' => 'letupass.123',

        ]);
        Registration::create([
            'first_name' => 'Admin',
            'middle_name' => 'Let',
            'last_name' => 'Upass',
            'role' => '3',
            'login_ID' => $user->login_ID,
            'isActive' => '1'
        ]);

        $user =User::create([
            'email' => 'admin2.letupass@gmail.com',
            'password' => 'letupass.2023',

        ]);
        Registration::create([
            'first_name' => 'Admin',
            'middle_name' => 'User',
            'last_name' => 'Account',
            'role' => '3',
            'login_ID' => $user->login_ID,
            'isActive' => '1'
        ]);
    }
}
