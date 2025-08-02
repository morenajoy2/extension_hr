<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Illuminate\Notifications\Notifiable;

class User extends Authenticatable
{
    use HasFactory, Notifiable;

    protected $fillable = [
        // Personal Info
        'photo',
        'first_name',
        'last_name',
        'middle_name',
        'birth_of_date',
        'gender',
        'school',
        'school_address',
        'contact_number',
        'address',

        // Employment Info
        'employee_id',
        'email',
        'password',
        'status',

        // Foreign Keys
        'role_id',
        'employment_type_id',
        'department_id',
        'department_team_id',
        'position_id',
        'strand_id',
        'group_id',
    ];

    protected $hidden = [
        'password',
        'remember_token',
    ];

    protected function casts(): array
    {
        return [
            'email_verified_at' => 'datetime',
            'password' => 'hashed',
        ];
    }

    public function role() {
        return $this->belongsTo(Role::class);
    }

    public function department() {
        return $this->belongsTo(Department::class);
    }

    public function departmentTeam() {
        return $this->belongsTo(DepartmentTeam::class);
    }

    public function position() {
        return $this->belongsTo(Position::class);
    }

    public function employmentType() {
        return $this->belongsTo(EmploymentType::class);
    }

    public function strand() {
        return $this->belongsTo(Strand::class);
    }

    public function group() {
        return $this->belongsTo(Group::class);
    }

    public function requirements() {
        return $this->hasMany(Requirement::class);
    }

    public function getRequirementStatusAttribute()
    {
        if ($this->requirements()->count() === 0) {
            return 'Incomplete';
        }

        if ($this->requirements()->where('status', 'Pending')->exists()) {
            return 'Pending';
        }

        if ($this->requirements()->where('status', 'Incomplete')->exists()) {
            return 'Incomplete';
        }

        return 'Completed';
    }

     public static function exitTypes(): array
    {
        return ['Exit Completion', 'Resignation', 'Termination'];
    }
}
