<?php

namespace App\Policies;

use App\Models\User;
use App\Models\UserExercise;

class UserExercisePolicy
{
    public function show(User $user, UserExercise $userExercise): bool
    {
        return $user->id === $userExercise->workout->user_id;
    }

    public function update(User $user, UserExercise $userExercise): bool
    {
        return $user->id === $userExercise->workout->user_id;
    }

    public function delete(User $user, UserExercise $userExercise): bool
    {
        return $user->id === $userExercise->workout->user_id;
    }
}
