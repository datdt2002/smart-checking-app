<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Department extends Model
{
    use HasFactory;
    protected $fillable = ['name', 'manager_id'];

    public function manager()
    {
        return $this->hasOne(User::class, 'manager_id');
    }

    public function users()
    {
        return $this->hasMany(User::class);
    }
}
