<?php

namespace Tests\Feature;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Illuminate\Foundation\Testing\WithFaker;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use Tests\TestCase;

class AuthTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic feature test example.
     */
    public function test_user_can_register(): void
    {
        $response = $this->postJson('/api/register', [
            'name' => 'Budi',
            'email' => 'budi@example.com',
            'password' => 'password123',
            'password_confirmation' => 'password123',
        ]);
        $response->assertStatus(201)
            ->assertJsonStructure(['token', 'user', 'message']);
        $this->assertDatabaseHas('users', ['email' => 'budi@example.com']);
    }

    public function test_user_can_login_with_valid_credentials(): void
    {
        User::factory()->create([
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);
        $response = $this->postJson('/api/login', [
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);
        $response->assertOk()
            ->assertJsonStructure(['token', 'message']);
    }

    public function test_user_can_logout_with_valid_token(): void
    {
        $user = User::factory()->create([
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $loginResponse = $this->postJson('/api/login', [
            'email' => 'budi@example.com',
            'password' => 'password123',
        ]);

        $token = $loginResponse->json('token');

        $this->withHeader('Authorization', 'Bearer ' . $token)
            ->postJson('/api/logout')
            ->assertOk()
            ->assertJson(['message' => 'Successfully logged out']);
    }
}
