<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Workout extends Model
{
    protected $fillable = ['name', 'user_id', 'schedule', 'status', 'comment'];

    public function user_exercises()
    {
        return $this->hasMany(UserExercise::class);
    }
    public function scopeActiveOrPending($query)
    {
        return $query->where('status', 'pending');
    }
}
