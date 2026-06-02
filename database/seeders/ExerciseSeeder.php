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
            ['name' => 'Push-ups','description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.','category' => "Upper Body,Bodyweight"],
            ['name' => 'Squats','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Lower Body,Bodyweight'],
            ['name' => 'Pull-ups','description' => 'A basic bodyweight exercise that targets the back and biceps.','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Plank','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Lunges','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Lower Body,Bodyweight'],
            ['name' => 'Deadlifts','description' => 'A basic bodyweight exercise that targets the back and biceps.','category' => 'Lower Body,Bodyweight'],
            ['name' => 'Bench Press','description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Shoulder Press','description' => 'A basic bodyweight exercise that targets the shoulders and triceps.','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Bicep Curls','description' => 'A basic bodyweight exercise that targets the biceps.','category' => 'Upper Body,Isolation'],
            ['name' => 'Tricep Dips','description' => 'A basic bodyweight exercise that targets the triceps.','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Leg Press','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Lower Body,Bodyweight'],
            ['name' => 'Calf Raises','description' => 'A basic bodyweight exercise that targets the calves.','category' => 'Lower Body,Isolation'],
            ['name' => 'Crunches','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Russian Twists','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Mountain Climbers','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Cardio'],
            ['name' => 'Burpees','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Full Body,Cardio'],
            ['name' => 'Jumping Jacks','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Full Body,Cardio'],
            ['name' => 'Dumbbell Rows','description' => 'A basic bodyweight exercise that targets the back and biceps.','category' => 'Upper Body,Strength'],
            ['name' => 'Kettlebell Swings','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Full Body,Strength'],
            ['name' => 'Bicycle Crunches','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Pull-ups','description' => 'A basic bodyweight exercise that targets the back and biceps.','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Plank','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Lunges','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Lower Body,Strength'],
            ['name' => 'Deadlifts','description' => 'A basic bodyweight exercise that targets the back and biceps.','category' => 'Lower Body,Strength'],
            ['name' => 'Bench Press','description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.','category' => 'Upper Body,Strength'],
            ['name' => 'Shoulder Press','description' => 'A basic bodyweight exercise that targets the shoulders and triceps.','category' => 'Upper Body,Strength'],
            ['name' => 'Bicep Curls','description' => 'A basic bodyweight exercise that targets the biceps.','category' => 'Upper Body,Isolation'],
            ['name' => 'Tricep Dips','description' => 'A basic bodyweight exercise that targets the triceps.','category' => 'Upper Body,Bodyweight'],
            ['name' => 'Leg Press','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Lower Body,Strength'],
            ['name' => 'Calf Raises','description' => 'A basic bodyweight exercise that targets the calves.','category' => 'Lower Body,Isolation'],
            ['name' => 'Crunches','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Russian Twists','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
            ['name' => 'Mountain Climbers','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Cardio'],
            ['name' => 'Burpees','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Full Body,Cardio'],
            ['name' => 'Jumping Jacks','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Full Body,Cardio'],
            ['name' => 'Dumbbell Rows','description' => 'A basic bodyweight exercise that targets the back and biceps.','category' => 'Upper Body,Strength'],
            ['name' => 'Kettlebell Swings','description' => 'A basic bodyweight exercise that targets the legs and glutes.','category' => 'Full Body,Strength'],
            ['name' => 'Bicycle Crunches','description' => 'A basic bodyweight exercise that targets the core.','category' => 'Core,Bodyweight'],
        ]);
    }
}
