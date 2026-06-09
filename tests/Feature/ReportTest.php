<?php

namespace Tests\Feature;

use App\Models\Exercise;
use App\Models\User;
use App\Models\UserExercise;
use App\Models\Workout;
use App\Models\Category;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class ReportTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_can_get_report_for_date_range(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.'
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);

        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'done',
        ]);

        UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/report?start_date=2026-06-01&end_date=2026-06-30');

        $response->assertOk()
            ->assertJsonStructure([
                'data' => [
                    'total_workouts',
                    'workout_missed',
                    'workout_done',
                    'total_exercise',
                    'exercise_done',
                    'sets_done',
                    'reps_done',
                ],
            ])
            ->assertJsonPath('data.total_workouts', 1)
            ->assertJsonPath('data.total_exercise', 1)
            ->assertJsonPath('data.workout_missed', 0)
            ->assertJsonPath('data.workout_done', 1)
            ->assertJsonPath('data.exercise_done', 1)
            ->assertJsonPath('data.sets_done', 3)
            ->assertJsonPath('data.reps_done', 10);
    }

    public function test_report_requires_date_range(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/report')
            ->assertStatus(422)
            ->assertJsonValidationErrors(['start_date', 'end_date']);
    }
}
