<?php

namespace Database\Seeders;

use App\Models\Branch;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class BranchSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $branches = [
            ['branchDescription' => 'Taguig City'],
            ['branchDescription' => 'Sta. Mesa, Manila'],
            ['branchDescription' => 'Quezon City'],
            ['branchDescription' => 'Parañaque City'],
            ['branchDescription' => 'Bataan'],
            ['branchDescription' => 'Maragondon, Cavite'],
        ];

        foreach ($branches as $branch) {
            Branch::create($branch);
        }
    }
}
