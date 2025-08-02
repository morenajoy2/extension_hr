<?php

namespace Database\Seeders;

use App\Models\Department;
use App\Models\DepartmentTeam;
use App\Models\EmploymentType;
use App\Models\Group;
use App\Models\Position;
use App\Models\Role;
use App\Models\Strand;
use App\Models\User;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        //Roles
        $adminRoleId = Role::where('role', 'Admin')->first()?->id;
        $teamLeaderRoleId = Role::where('role', 'Team Leader')->first()?->id;

        //Employment Types
        $k12Id = EmploymentType::where('type_name', 'K-12 Work Immersion')->first()?->id;
        $collegeInternId = EmploymentType::where('type_name', 'College Internship')->first()?->id;

        //Departments
        $managementDeptId = Department::where('department_name', 'Management')->first()?->id;
        $digitalOpsDeptId = Department::where('department_name', 'Digital Operations')->first()?->id;

        //Department Teams
        $corpServicesTeamId = DepartmentTeam::where('team_name', 'Corporate Services')->first()?->id;
        $webDevTeamId = DepartmentTeam::where('team_name', 'Web & Mobile Development')->first()?->id;
        $cliServicesTeamId = DepartmentTeam::where('team_name', 'Client Services')->first()?->id;
        $creMultimediaTeamId = DepartmentTeam::where('team_name', 'Creative Multimedia')->first()?->id;

        //Positions
        $frontWebId = Position::where('position_name', 'Front-End Web & Mobile Development')->first()?->id;
        $backWebId = Position::where('position_name', 'Back-End Web & Mobile Development')->first()?->id;
        $multimediaId = Position::where('position_name', 'Creative Multimedia')->first()?->id;
        $aiPosId = Position::where('position_name', 'AI Automation')->first()?->id;
        $grapDesignId = Position::where('position_name', 'Graphic Design')->first()?->id;
        $copywriterId = Position::where('position_name', 'Copywriter')->first()?->id;
        $hrId = Position::where('position_name', 'HR')->first()?->id;
        $financeId = Position::where('position_name', 'Finance')->first()?->id;
        $accountingId = Position::where('position_name', 'Accounting')->first()?->id;
        $pmPosId = Position::where('position_name', 'Project Management')->first()?->id;
        $salesId = Position::where('position_name', 'Sales')->first()?->id;
        $marketId = Position::where('position_name', 'Marketing')->first()?->id;
        $accountsId = Position::where('position_name', 'Accounts')->first()?->id;
        $bsDevId = Position::where('position_name', 'Business Development')->first()?->id;

        //Strands
        $stemStrandId = Strand::where('strand_name', 'STEM')->first()?->id;
        $humssStrandId = Strand::where('strand_name', 'HUMSS')->first()?->id;
        $gasStrandId = Strand::where('strand_name', 'GAS')->first()?->id;
        $aniStrandId = Strand::where('strand_name', 'ICT-Animation')->first()?->id;
        $progStrandId = Strand::where('strand_name', 'ICT-Programming')->first()?->id;

        //Groups
        $group1Id = Group::where('group_no', 1)->first()?->id;

        //Admin,Team Leader,Group Leader, member
        User::create([
            'employee_id' => 1001,
            'first_name' => 'Eren',
            'last_name' => 'Buenavista',
            'birth_of_date' => '2004-04-14',
            'school' => 'PUP Manila',
            'employment_type_id' => $k12Id,
            'role_id' => $teamLeaderRoleId,
            'strand_id' => $stemStrandId,
            'department_id' => $digitalOpsDeptId,
            'department_team_id' => $webDevTeamId,
            'position_id' => $frontWebId,
            'status' => 'Active',
            'contact_number' => '09175559988',
            'gender' => 'Male',
            'email' => 'admin@example.com',
            'password' => Hash::make('password'),
            'group_id' => $group1Id,
        ]);

        //HR
        User::create([
            'employee_id' => 1002,
            'first_name' => 'HR',
            'birth_of_date' => '2004-04-14',
            'school' => 'PUP Manila',
            'employment_type_id' => $collegeInternId,
            'role_id' => $teamLeaderRoleId,
            'department_id' => $managementDeptId,
            'department_team_id' => $corpServicesTeamId,
            'position_id' => $hrId,
            'status' => 'Active',
            'contact_number' => '09175559988',
            'gender' => 'Male',
            'email' => 'hr@example.com',
            'password' => Hash::make('password'),
            'group_id' => $group1Id,
        ]); 
    }
}
