<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;
use App\Models\Workout;
use App\Models\Exercise;
use App\Models\UserExercise;

class UserExerciseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_create_a_new_user_exercise(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'category' => 'Upper Body,Bodyweight',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/user-exercise', [
                'workout_id' => $workout->id,
                'exercise_id' => $exercise->id,
                'description' => 'Test',
                'kilograms' => 10,
                'set_count' => 3,
                'rep_count' => 10,
            ]);
        $response->assertCreated()
            ->assertJsonStructure(['message', 'userExercise']);
        $this->assertDatabaseHas('user_exercises', ['workout_id' => $workout->id, 'exercise_id' => $exercise->id]);
    }
    public function test_user_can_get_a_specific_user_exercise(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'category' => 'Upper Body,Bodyweight',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->getJson('/api/user-exercise/' . $userExercise->id);
        $response->assertOk()
            ->assertJsonStructure(['userExercise']);
        $this->assertDatabaseHas('user_exercises', ['id' => $userExercise->id]);
    }
    public function test_user_can_update_a_user_exercise(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'category' => 'Upper Body,Bodyweight',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson('/api/user-exercise/' . $userExercise->id, [
                'description' => 'Test',
                'kilograms' => 10,
                'set_count' => 3,
                'rep_count' => 10,
            ]);
        $response->assertOk()
            ->assertJsonStructure(['message', 'userExercise']);
        $this->assertDatabaseHas('user_exercises', ['description' => 'Test', 'kilograms' => 10, 'set_count' => 3, 'rep_count' => 10]);
    }
    public function test_user_can_delete_a_user_exercise(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exercise = Exercise::create([
            'name' => 'Push-ups',
            'description' => 'Test',
            'category' => 'Upper Body,Bodyweight',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exercise->id,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/user-exercise/' . $userExercise->id);
        $response->assertOk()
            ->assertJsonStructure(['message']);
        $this->assertDatabaseMissing('user_exercises', ['id' => $userExercise->id]);
    }
}
