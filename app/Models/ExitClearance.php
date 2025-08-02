<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ExitClearance extends Model
{
    use HasFactory;

    protected $fillable = [
        'requirement_id',
    'user_id',
    'department_id',
    'department_team_id',
    'role_id',
    'group_id',
    'exit_type',
    'task_turnover_role', 
    'task_list',
    'team_leader_access_confirmation',
    'hr_access_confirmation',
    'e_signature',
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

     public function group()
    {
        return $this->belongsTo(Group::class);
    }

    public function taskTurnoverRole()
{
    return $this->belongsTo(Role::class, 'task_turnover_role');
}
}
