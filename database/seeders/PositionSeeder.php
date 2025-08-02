<?php

namespace Database\Seeders;

use App\Models\Position;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class PositionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Position::insert([
            ['position_name' => 'Front-End Web & Mobile Development'],
            ['position_name' => 'Back-End Web & Mobile Development'],
            ['position_name' => 'Creative Multimedia'],
            ['position_name' => 'AI Automation'],
            ['position_name' => 'Graphic Design'],
            ['position_name' => 'Copywriter'],
            ['position_name' => 'HR'],
            ['position_name' => 'Finance'],
            ['position_name' => 'Accounting'],
            ['position_name' => 'Project Management'],
            ['position_name' => 'Sales'],
            ['position_name' => 'Marketing'],
            ['position_name' => 'Accounts'],
            ['position_name' => 'Business Development'],
        ]);
    }
}
