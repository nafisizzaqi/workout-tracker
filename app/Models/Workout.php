<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['name', 'user_id', 'schedule', 'status'];
    public function user_exercises()
    {
        return $this->hasMany(UserExercise::class);
    }
}
