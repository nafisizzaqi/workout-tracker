<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Exercise;

class ExerciseSeeder extends Seeder
{
    use WithoutModelEvents;
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        Exercise::insert([
            ['name' => 'Push-ups', 'category' => "Upper Body,Bodyweight"],
            ['name' => 'Squats', 'category' => 'Lower Body,Bodyweight'],
            ['name' => 'Pull-ups', 'category' => 'Upper Body,Bodyweight'],
            ['name' => 'Plank', 'category' => 'Core,Bodyweight'],
            ['name' => 'Lunges', 'category' => 'Lower Body,Strength'],
            ['name' => 'Deadlifts','category' => 'Lower Body,Strength'],
            ['name' => 'Bench Press','category' => 'Upper Body,Strength'],
            ['name' => 'Shoulder Press','category' => 'Upper Body,Strength'],
            ['name' => 'Bicep Curls','category' => 'Upper Body,Isolation'],
            ['name' => 'Tricep Dips','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Leg Press','category' => 'Lower Body,Strength'],
            ['name' => 'Calf Raises','category' => 'Lower Body,Isolation'],
            ['name' => 'Crunches','category' => 'Core,Bodyweight'],
            ['name' => 'Russian Twists','category' => 'Core,Bodyweight'],
            ['name' => 'Mountain Climbers','category' => 'Core,Cardio'],
            ['name' => 'Burpees','category' => 'Full Body,Cardio'],
            ['name' => 'Jumping Jacks','category' => 'Full Body,Cardio'],
            ['name' => 'Dumbbell Rows','category' => 'Upper Body,Strength'],
            ['name' => 'Kettlebell Swings','category' => 'Full Body,Strength'],
            ['name' => 'Bicycle Crunches','category' => 'Core,Bodyweight'],
        ]);
    }
}
