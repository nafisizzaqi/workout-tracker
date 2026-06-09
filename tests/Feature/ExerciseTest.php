<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Tests\TestCase;
use App\Models\User;
use Tymon\JWTAuth\Facades\JWTAuth;
use Illuminate\Support\Facades\DB;

class ExerciseTest extends TestCase
{
    /**
     * A basic feature test example.
     */
    public function test_user_can_get_all_exercises(): void
    {
        $user = User::factory()->create();
        $token = JWTAuth::fromUser($user);
        $exerciseId = DB::table('exercises')->insert([
            'name' => 'Push-ups',
            'description' => 'Test',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $categoryId = DB::table('categories')->insert([
            'name' => 'Upper Body, Bory Weight',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        DB::table('exercise_categories')->insert([
            'exercise_id' => $exerciseId,
            'category_id' => $categoryId,
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/exercise-list');
        $response->assertOk()
        ->assertJsonStructure([
            'exercises' => [
                '*' => [
                    'id',
                    'name',
                    'categories' => [
                        '*' => ['id', 'name'],
                    ],
                ],
            ],
        ]);
        $this->assertCount(1, $response->json('exercises'));
    }
}
