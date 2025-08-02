<?php

namespace App\Models;

use App\Models\Department;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;


class DepartmentTeam extends Model
{
    use HasFactory;

    protected $fillable = ['team_name', 'department_id'];

    public function department()
    {
        return $this->belongsTo(Department::class);
    }
}
