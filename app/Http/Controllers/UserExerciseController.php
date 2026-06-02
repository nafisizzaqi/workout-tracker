<?php

namespace App\Http\Controllers;

use App\Models\UserExercise;
use App\Models\Workout;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Gate;

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

        $workout = Workout::where('id', $validated['workout_id'])
            ->where('user_id', $request->user()->id)
            ->first();

        if (! $workout) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }

        $userExercise = UserExercise::create($validated);
        return response()->json(['message' => 'User exercise created successfully', 'userExercise' => $userExercise], 201);
    }

    public function show(UserExercise $userExercise)
    {
        if (Gate::denies('show', $userExercise)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        return response()->json(['userExercise' => $userExercise], 200);
    }

    public function update(Request $request, UserExercise $userExercise)
    {
        if (Gate::denies('update', $userExercise)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
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
        if (Gate::denies('delete', $userExercise)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $userExercise->delete();
        return response()->json(['message' => 'User exercise deleted successfully'], 200);
    }
}
