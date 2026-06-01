<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserExercise extends Model
{
    protected $fillable = ['description', 'exercise_id', 'workout_id', 'kilograms', 'set_count', 'rep_count'];

    public function workout()
    {
        return $this->belongsTo(Workout::class);
    }
}
