<?php

namespace Database\Seeders;

use App\Models\Role;
use App\Models\EmploymentType;
use App\Models\Department;
use App\Models\DepartmentTeam;
use App\Models\Strand;
use App\Models\Position;
use App\Models\Group;
use App\Models\User;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
            RoleSeeder::class,
            EmploymentTypeSeeder::class,
            DepartmentSeeder::class,
            DepartmentTeamSeeder::class,
            StrandSeeder::class,
            PositionSeeder::class,
            GroupSeeder::class,
            UserSeeder::class,
        ]);
        
    }
}
