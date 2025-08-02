<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use App\Models\User;

class Position extends Model
{
    use HasFactory;

    protected $fillable = [
        'position_name',
    ];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
