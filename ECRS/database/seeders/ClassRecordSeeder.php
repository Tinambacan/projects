<?php

namespace Database\Seeders;

use App\Models\ClassRecord;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClassRecordSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // ClassRecord::create([
        //     'schoolYear' => '2024',
        //     'chairPerson' => 'Dr. John Smith',
        //     'modeOfLearning' => 1,
        //     'schedDay' => 'Monday',
        //     'schedTime' => '08:00:00',
        //     'semester' => 1,
        //     'yearLevel' => 4,
        //     'classImg' => '',
        //     'template' => 1,
        //     'recordType' => 1,
        //     'programID' => 1,
        //     'courseID' => 1,
        //     'loginID' => 1,
        // ]);

        // ClassRecord::create([
        //     'schoolYear' => '2024',
        //     'chairPerson' => 'Prof. Jane Doe',
        //     'modeOfLearning' => 2,
        //     'schedDay' => 'Tuesday',
        //     'schedTime' => '10:00:00',
        //     'semester' => 2,
        //     'yearLevel' => 3,
        //     'classImg' => '',
        //     'template' => 2,
        //     'recordType' => 2,
        //     'programID' => 2,
        //     'courseID' => 2,
        //     'loginID' => 2,
        // ]);

         ClassRecord::create([
            'schoolYear' => '2024-2025',
            'chairPerson' => 'Prof. Doe Jane',
            'modeOfLearning' => 2,
            'schedDay' => 'M/W/F',
            'schedTime' => '10:00 - 13:00',
            'semester' => 2,
            'yearLevel' => 3,
            'classImg' => '',
            'template' => '',
            'recordType' => 1,
            'isArchived' => 0,
            'programID' => 1,
            'courseID' => 13,
            'loginID' => 2,
        ]);
    }
}
