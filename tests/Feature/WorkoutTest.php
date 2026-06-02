<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use App\Models\Workout;
use App\Models\UserExercise;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class WorkoutTest extends TestCase
{
    use RefreshDatabase;

    /**
     * A basic feature test example.
     */
    // public function test_user_can_get_all_exercises(): void
    // {
    //     $user = User::factory()->create();
    //     $token = JWTAuth::fromUser($user);
    //     DB::table('exercises')->insert([
    //         'name' => 'Push-ups',
    //         'description' => 'Test',
    //         'category' => 'Upper Body,Bodyweight',
    //         'created_at' => now(),
    //         'updated_at' => now(),
    //     ]);
    //     $response = $this->withHeader('Authorization', 'Bearer ' . $token)
    //         ->getJson('/api/exercise-list');
    //     $response->assertOk()
    //         ->assertJsonStructure(['exercises' => ['*' => ['id', 'name', 'category']]]);
    //     $this->assertCount(1, $response->json('exercises'));
    // }

    public function test_user_can_create_a_new_workout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        $exerciseId = DB::table('exercises')->insertGetId([
            'name' => 'Push-ups',
            'description' => 'Test exercise',
            'category' => json_encode(['Upper Body', 'Bodyweight']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/workout', [
                'name' => 'Workout 1',
                'schedule' => '2026-06-02 10:00:00',
                'user_exercises' => [
                    [
                        'exercise_id' => $exerciseId,
                        'description' => 'Test',
                        'kilograms' => 10,
                        'set_count' => 3,
                        'rep_count' => 10,
                    ],
                ],
            ]);

        $response->assertCreated()
            ->assertJsonStructure(['message', 'workout', 'user_exercises']);
        $this->assertDatabaseHas('workouts', ['name' => 'Workout 1']);
        $this->assertDatabaseHas('user_exercises', ['exercise_id' => $exerciseId]);
    }
    public function test_user_can_list_pending_workouts_sorted_by_schedule(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);

        Workout::create([
            'name' => 'Later',
            'user_id' => $user->id,
            'schedule' => '2026-06-05 18:00:00',
            'status' => 'pending',
        ]);
        Workout::create([
            'name' => 'Sooner',
            'user_id' => $user->id,
            'schedule' => '2026-06-03 08:00:00',
            'status' => 'pending',
        ]);
        Workout::create([
            'name' => 'Done workout',
            'user_id' => $user->id,
            'schedule' => '2026-06-01 08:00:00',
            'status' => 'done',
        ]);
        Workout::create([
            'name' => 'Missed workout',
            'user_id' => $user->id,
            'schedule' => '2026-06-01 09:00:00',
            'status' => 'missed',
        ]);

        $otherUser = User::factory()->create();
        Workout::create([
            'name' => 'Other user',
            'user_id' => $otherUser->id,
            'schedule' => '2026-06-02 11:00:00',
            'status' => 'pending',
        ]);

        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/workout');

        $response->assertOk()
            ->assertJsonStructure(['workouts' => ['*' => ['id', 'name', 'schedule', 'status']]]);

        $workouts = $response->json('workouts');
        $this->assertCount(2, $workouts);
        $this->assertEquals('Sooner', $workouts[0]['name']);
        $this->assertEquals('Later', $workouts[1]['name']);
        $this->assertEquals('pending', $workouts[0]['status']);
    }

    public function test_user_can_update_a_workout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exerciseId = DB::table('exercises')->insertGetId([
            'name' => 'Push-ups',
            'description' => 'Test exercise',
            'category' => json_encode(['Upper Body', 'Bodyweight']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $userExercise = UserExercise::create([
            'workout_id' => $workout->id,
            'exercise_id' => $exerciseId,
            'description' => 'Test',
            'kilograms' => 10,
            'set_count' => 3,
            'rep_count' => 10,
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->patchJson('/api/workout/' . $workout->id, [
                'name' => 'Updated Workout',
                'schedule' => '2026-06-02 12:00:00',
                'status' => 'done',
                'user_exercises' => [
                    [
                        'id' => $userExercise->id,
                        'exercise_id' => $exerciseId,
                        'description' => 'Test',
                        'kilograms' => 10,
                        'set_count' => 3,
                        'rep_count' => 10,
                    ],
                ],
            ]);
        $response->assertOk()
            ->assertJsonStructure(['message', 'workout']);
        $this->assertDatabaseHas('workouts', ['name' => 'Updated Workout', 'schedule' => '2026-06-02 12:00:00', 'status' => 'done']);
        $this->assertDatabaseHas('user_exercises', ['exercise_id' => $exerciseId, 'description' => 'Test', 'kilograms' => 10, 'set_count' => 3, 'rep_count' => 10]);
    }

    public function test_user_can_delete_a_workout(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $exerciseId = DB::table('exercises')->insertGetId([
            'name' => 'Push-ups',
            'description' => 'Test exercise',
            'category' => json_encode(['Upper Body', 'Bodyweight']),
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->withHeader('Authorization', 'Bearer ' . $token)
            ->deleteJson('/api/workout/' . $workout->id);
        $response->assertOk()
            ->assertJsonStructure(['message']);
        $this->assertDatabaseMissing('workouts', ['id' => $workout->id]);
        $this->assertDatabaseMissing('user_exercises', ['exercise_id' => $exerciseId]);
    }
}
