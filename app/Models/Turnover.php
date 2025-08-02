<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Turnover extends Model
{
    use HasFactory;

    protected $fillable = [
        'requirement_id',
        'user_id',
        'department_id',
        'department_team_id',
        'role_id',
        'employment_type_id',
        'job_title',
        'orientation_date',
        'first_day_date',
        'last_day_date',
        'exit_date',
        'total_worked_hours_required',
        'recommended_employee_id',
        'recommended_employee_name',
        'task_list',
        'new_owner_transfer_list',
        'confirmation_access_credentials',
        'dpt_team_leader_employee_id',
        'dpt_team_leader_employee_name',
        'hr_team_leader_employee_id',
        'hr_team_leader_employee_name',
        'e_signature'
    ];

    public function requirement()
    {
        return $this->belongsTo(Requirement::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function department()
    {
        return $this->belongsTo(Department::class);
    }

     public function departmentTeam()
    {
        return $this->belongsTo(DepartmentTeam::class);
    }

     public function role()
    {
        return $this->belongsTo(Role::class);
    }

    public function recommendedEmployee()
    {
        return $this->belongsTo(User::class, 'recommended_employee_id');
    }

    public function departmentTeamLeader()
    {
        return $this->belongsTo(User::class, 'dpt_team_leader_employee_id');
    }

    public function hrTeamLeader()
    {
        return $this->belongsTo(User::class, 'hr_team_leader_employee_id');
    }

    public function employmentType()
    {
        return $this->belongsTo(EmploymentType::class, 'employment_type_id');
    }
}
