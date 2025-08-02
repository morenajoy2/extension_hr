<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class WeeklyReport extends Model
{
    use HasFactory;

    protected $fillable = [
        'requirement_id',
        'user_id',
        'department_id',
        'department_team_id',
        'role_id',
        'from_date',
        'to_date',
        'worked_hours',
        'total_hours',
        'remaining_hours',
        'doc_upload',
        'pdf_upload',
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

}
