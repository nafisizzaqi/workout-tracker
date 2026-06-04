<?php

namespace Tests\Unit;

use Illuminate\Foundation\Testing\RefreshDatabase;
use Tests\TestCase;
use App\Models\User;
use App\Models\Workout;

class WorkoutPolicyTest extends TestCase
{
    use RefreshDatabase;
    /**
     * A basic unit test example.
     */
    public function test_policy_workout_show_true()
    {
        $user = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $user->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $this->assertTrue($user->can('show', $workout));
        $this->assertTrue($user->can('update', $workout));
        $this->assertTrue($user->can('delete', $workout));
    }
    public function test_policy_workout_update_false()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $this->assertFalse($intruder->can('update', $workout));
    }
    public function test_policy_workout_delete_false()
    {
        $owner = User::factory()->create();
        $intruder = User::factory()->create();
        $workout = Workout::create([
            'name' => 'Workout 1',
            'user_id' => $owner->id,
            'schedule' => '2026-06-02 10:00:00',
            'status' => 'pending',
        ]);
        $this->assertFalse($intruder->can('delete', $workout));
    }
}
