<?php

namespace Database\Seeders;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ProfSeeder extends Seeder
{
    public function run(): void
    {
        //
        $user = User::create([
            'email' => 'professor@gmail.com',
            'password' => 'letupass.123',

        ]);
        Registration::create([
            'first_name' => 'prof',
            'middle_name' => 'let',
            'last_name' => 'upass',
            'role' => '2',
            'login_ID' => $user->login_ID,
            'isActive' => '1'
        ]);
    }
}
