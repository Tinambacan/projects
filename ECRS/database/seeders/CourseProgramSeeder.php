<?php

namespace Database\Seeders;

use App\Models\Courses;
use App\Models\Programs;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class CourseProgramSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $program = Programs::create([
            'programCode' => 'DICT',
            'programTitle' => 'Diploma in Information Communication and Technology',
        ]);

        Courses::create([
            'courseCode' => 'ACCO 20213',
            'courseTitle' => 'Accounting Principles',
            'programID' => $program->programID
        ]);
    }
}
