<?php

namespace App\Models;

use App\Models\User;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Strand extends Model
{
    use HasFactory;

    protected $fillable = ['strand_name'];

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
