<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Exercise extends Model
{
    protected $fillable = ['name', 'category'];

    public function categories()
    {
        return $this->belongsToMany(Category::class, 'exercise_categories');
    }
}
