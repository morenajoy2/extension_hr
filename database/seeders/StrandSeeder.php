<?php

namespace Database\Seeders;

use App\Models\Strand;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class StrandSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Strand::insert([
            ['strand_name' => 'STEM'],
            ['strand_name' => 'HUMSS'],
            ['strand_name' => 'GAS'],
            ['strand_name' => 'ICT-Animation'],
            ['strand_name' => 'ICT-Programming'],
        ]);
    }
}
