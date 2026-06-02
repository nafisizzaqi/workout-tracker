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
        DB::table('exercises')->insert([
            'name' => 'Push-ups',
            'description' => 'Test',
            'category' => 'Upper Body,Bodyweight',
            'created_at' => now(),
            'updated_at' => now(),
        ]);
        $response = $this->withHeader('Authorization', 'Bearer '.$token)
            ->getJson('/api/exercise-list');
        $response->assertOk()
            ->assertJsonStructure(['exercises' => ['*' => ['id', 'name', 'category']]]);
        $this->assertCount(1, $response->json('exercises'));
    }
}
