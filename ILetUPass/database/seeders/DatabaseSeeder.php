<?php

namespace Database\Seeders;

// use Illuminate\Database\Console\Seeds\WithoutModelEvents;

use App\Models\Registration;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // \App\Models\User::factory(10)->create();

        // \App\Models\User::factory()->create([
        //     'name' => 'Test User',
        //     'email' => 'test@example.com',
        // ]);

        $user = User::create([
            'email' => 'john@gmail.com',
            'password' => 'sample1234',
            'student_num' => 'TUPM-20-1552',
        ]);
        Registration::create([
            'first_name' => 'John',
            'middle_name' => 'D.',
            'last_name' => 'Doe',
            'role' => '1',
            'login_ID' => $user->login_ID,
            'isActive' => '1'
        ]);
    }
}
