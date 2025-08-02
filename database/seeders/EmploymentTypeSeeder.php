<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\EmploymentType;


class EmploymentTypeSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        EmploymentType::insert([
            ['type_name' => 'K-12 Work Immersion'],
            ['type_name' => 'College Internship'],
            ['type_name' => 'Graduate Apprenticeship'],
            ['type_name' => 'Part-Time Job'],
            ['type_name' => 'Full-Time Job'],
        ]);
    }
}
