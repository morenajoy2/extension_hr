<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\DepartmentTeam;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DepartmentTeamSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $digitalOpsId = Department::where('department_name', 'Digital Operations')->first()?->id;
        $managementId = Department::where('department_name', 'Management')->first()?->id;

        DepartmentTeam::insert([
            ['department_id' => $digitalOpsId, 'team_name' => 'Web & Mobile Development'],
            ['department_id' => $digitalOpsId, 'team_name' => 'Creative Multimedia'],
            ['department_id' => $managementId, 'team_name' => 'Client Services'],
            ['department_id' => $managementId, 'team_name' => 'Corporate Services'],
        ]);
    }
}
