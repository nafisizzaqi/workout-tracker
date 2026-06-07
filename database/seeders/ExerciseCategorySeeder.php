<?php

namespace Database\Seeders;

use App\Models\Category;
use App\Models\Exercise;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ExerciseCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = Category::pluck('id', 'name');
        $relations = [
            'Push-ups'          => ['Upper Body,Bodyweight'],
            'Squats'            => ['Lower Body,Bodyweight'],
            'Pull-ups'          => ['Upper Body,Bodyweight'],
            'Plank'             => ['Core,Bodyweight'],
            'Lunges'            => ['Lower Body,Bodyweight'],
            'Deadlifts'         => ['Lower Body,Strength'],
            'Bench Press'       => ['Upper Body,Strength'],
            'Shoulder Press'    => ['Upper Body,Strength'],
            'Bicep Curls'       => ['Upper Body,Isolation'],
            'Tricep Dips'       => ['Upper Body,Isolation'],
            'Leg Press'         => ['Lower Body,Strength'],
            'Calf Raises'       => ['Lower Body,Isolation'],
            'Crunches'          => ['Core,Bodyweight'],
            'Russian Twists'    => ['Core,Bodyweight'],
            'Mountain Climbers' => ['Core,Cardio'],
            'Burpees'           => ['Full Body,Cardio'],
            'Jumping Jacks'     => ['Full Body,Cardio'],
            'Dumbbell Rows'     => ['Upper Body,Strength'],
            'Kettlebell Swings' => ['Full Body,Strength'],
            'Bicycle Crunches'  => ['Core,Bodyweight'],
        ];

        foreach ($relations as $exerciseName => $categoryNames) {
            $exercises = Exercise::where('name', $exerciseName)->get();

            foreach ($exercises as $exercise) {
                $categoryIds = [];
                foreach ($categoryNames as $catName) {
                    if (isset($categories[$catName])) {
                        $categoryIds[] = $categories[$catName];
                    }
                }
                if (!empty($categoryIds)) {
                    $exercise->categories()->syncWithoutDetaching($categoryIds);
                }
            }
        }
    }
}
