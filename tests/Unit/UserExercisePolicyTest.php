<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Workout;
use App\Models\Exercise;
use App\Models\UserExercise;
use App\Models\Category;

class UserExercisePolicyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_policy_user_exercise_show_true()
    {
        $user = User::factory()->create();
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $this->assertTrue($user->can('show', $userExercise));
        $this->assertTrue($user->can('update', $userExercise));
        $this->assertTrue($user->can('delete', $userExercise));
    }
    public function test_policy_user_exercise_update_false()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $this->assertFalse($intruder->can('update', $userExercise));
    }
    public function test_policy_user_exercise_delete_false()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $this->assertFalse($intruder->can('delete', $userExercise));
    }
}
