<?php

namespace Tests\Feature;

use App\Models\Category;
use App\Models\Exercise;
use App\Models\User;
use App\Models\UserExercise;
use App\Models\Workout;
use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use Tymon\JWTAuth\Facades\JWTAuth;

class AuthorizationTest extends TestCase
{
    use RefreshDatabase;

    public function test_user_cannot_view_another_users_workout(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);

        $token = JWTAuth::fromUser($intruder);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/workout/'.$workout->id)
            ->assertForbidden()
            ->assertJson(['message' => 'Unauthorized']);
    }

    public function test_user_cannot_update_another_users_workout(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);

        $token = JWTAuth::fromUser($intruder);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->putJson('/api/workout/'.$workout->id, ['name' => 'Hacked'])
            ->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_workout(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);

        $token = JWTAuth::fromUser($intruder);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/workout/'.$workout->id)
            ->assertForbidden();

        $this->assertDatabaseHas('workouts', ['id' => $workout->id]);
    }

    public function test_user_cannot_create_user_exercise_on_another_users_workout(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.'
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);

        $token = JWTAuth::fromUser($intruder);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->postJson('/api/user-exercise', [
                'workout_id' => $workout->id,
                'exercise_id' => $exercise->id,
                'description' => 'Test',
                'kilograms' => 10,
                'set_count' => 3,
                'rep_count' => 10,
            ])
            ->assertForbidden();

        $this->assertDatabaseMissing('user_exercises', ['workout_id' => $workout->id]);
    }

    public function test_user_cannot_view_another_users_user_exercise(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.'
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
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

        $token = JWTAuth::fromUser($intruder);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/user-exercise/'.$userExercise->id)
            ->assertForbidden();
    }

    public function test_user_cannot_delete_another_users_user_exercise(): void
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'A basic bodyweight exercise that targets the chest, shoulders, and triceps.'
        ]);
        $category = Category::create(['name' => 'Upper Body, Body Weight']);
        $exercise->categories()->attach($category->id);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
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

        $token = JWTAuth::fromUser($intruder);

        $this->withHeader('Authorization', 'Bearer '.$token)
            ->deleteJson('/api/user-exercise/'.$userExercise->id)
            ->assertForbidden();

        $this->assertDatabaseHas('user_exercises', ['id' => $userExercise->id]);
    }
}
