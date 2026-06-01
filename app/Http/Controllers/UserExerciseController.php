<?php

namespace App\Http\Controllers;

use App\Models\UserExercise;
use Illuminate\Http\Request;

class UserExerciseController extends Controller
{
    public function store(Request $request)
    {
        $validated = $request->validate([
            'workout_id' => 'required|exists:workouts,id',
            'exercise_id' => 'required|exists:exercises,id',
            'description' => 'required|string|max:255',
            'kilograms' => 'required|numeric|min:0',
            'set_count' => 'required|integer|min:1',
            'rep_count' => 'required|integer|min:1',
        ]);

        $userExercise = UserExercise::create($validated);
        return response()->json(['message' => 'User exercise created successfully', 'userExercise' => $userExercise], 201);
    }

    public function show(UserExercise $userExercise)
    {
        return response()->json(['userExercise' => $userExercise], 200);
    }

    public function update(Request $request, UserExercise $userExercise)
    {
        $validated = $request->validate([
            'description' => 'required|string|max:255',
            'kilograms' => 'required|numeric|min:0',
            'set_count' => 'required|integer|min:1',
            'rep_count' => 'required|integer|min:1',
        ]);

        $userExercise->update($validated);
        return response()->json(['message' => 'User exercise updated successfully', 'userExercise' => $userExercise], 200);
    }

    public function destroy(UserExercise $userExercise)
    {
        $userExercise->delete();
        return response()->json(['message' => 'User exercise deleted successfully'], 200);
    }
}
