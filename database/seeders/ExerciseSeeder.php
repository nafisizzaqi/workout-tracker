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
            ['name' => 'Push-ups','description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.'],
            ['name' => 'Squats','description' => 'A basic bodyweight exercise that targets the legs and glutes.'],
            ['name' => 'Pull-ups','description' => 'A basic bodyweight exercise that targets the back and biceps.'],
            ['name' => 'Plank','description' => 'A basic bodyweight exercise that targets the core.'],
            ['name' => 'Lunges','description' => 'A basic bodyweight exercise that targets the legs and glutes.'],
            ['name' => 'Deadlifts','description' => 'A basic bodyweight exercise that targets the back and biceps.'],
            ['name' => 'Bench Press','description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.'],
            ['name' => 'Shoulder Press','description' => 'A basic bodyweight exercise that targets the shoulders and triceps.'],
            ['name' => 'Bicep Curls','description' => 'A basic bodyweight exercise that targets the biceps.'],
            ['name' => 'Tricep Dips','description' => 'A basic bodyweight exercise that targets the triceps.'],
            ['name' => 'Leg Press','description' => 'A basic bodyweight exercise that targets the legs and glutes.'],
            ['name' => 'Calf Raises','description' => 'A basic bodyweight exercise that targets the calves.'],
            ['name' => 'Crunches','description' => 'A basic bodyweight exercise that targets the core.'],
            ['name' => 'Russian Twists','description' => 'A basic bodyweight exercise that targets the core.'],
            ['name' => 'Mountain Climbers','description' => 'A basic bodyweight exercise that targets the core.'],
            ['name' => 'Burpees','description' => 'A basic bodyweight exercise that targets the core.'],
            ['name' => 'Jumping Jacks','description' => 'A basic bodyweight exercise that targets the core.'],
            ['name' => 'Dumbbell Rows','description' => 'A basic bodyweight exercise that targets the back and biceps.'],
            ['name' => 'Kettlebell Swings','description' => 'A basic bodyweight exercise that targets the legs and glutes.'],
            ['name' => 'Bicycle Crunches','description' => 'A basic bodyweight exercise that targets the core.'],
        ]);
    }
}
