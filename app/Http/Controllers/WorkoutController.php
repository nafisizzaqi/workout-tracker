<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workout;
use Illuminate\Validation\Rule;
use App\Models\UserExercise;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Gate;
class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workouts = Workout::query()
            ->where('user_id', $request->user()->id)
            ->activeOrPending()
            ->with('user_exercises')
            ->orderBy('schedule')
            ->orderBy('id')
            ->get();

        return response()->json(['workouts' => $workouts], 200);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'schedule' => 'required|date_format:Y-m-d H:i:s',            
            'user_exercises' => 'required|array',
            'user_exercises.*.exercise_id' => 'required|integer|exists:exercises,id|distinct',
            'user_exercises.*.description' => 'required|string|max:255',
            'user_exercises.*.kilograms' => 'required|numeric|min:0',
            'user_exercises.*.set_count' => 'required|integer|min:1',
            'user_exercises.*.rep_count' => 'required|integer|min:1',
            'status' => [Rule::in(['done', 'pending', 'missed']), 'nullable'],
        ]);

        $workout = $validated;
        unset($workout['user_exercises'], $workout['schedule']);
        $workout['user_id'] = $request->user()->id;
        $workout = Workout::create($workout);
        $workout->update(['schedule' => $validated['schedule']]);
        $now = now();
        $user_exercises_data = [];
        foreach ($request->user_exercises as $userExercise) {
            $user_exercises_data[] = array_merge($userExercise, [
                'workout_id' => $workout->id,
                'created_at' => $now,
                'updated_at' => $now,
            ]);
        }
        $user_exercises = UserExercise::insert($user_exercises_data);
        if (!$user_exercises) {
            return response()->json(['message' => 'Failed to create user exercises'], 500);
        }
        return response()->json(['message' => 'Workout created successfully', 'workout' => $workout, 'user_exercises' => $user_exercises], 201);
    }

    /**
     * Display the specified resource.
     */
    public function show(Workout $workout)
    {
        if (Gate::denies('show', $workout)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $data = $workout->load('user_exercises');
        return response()->json(['workout' => $data], 200);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Workout $workout)
    {
        if (Gate::denies('update', $workout)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => [Rule::in(['done', 'pending', 'missed']), 'nullable'],
            'schedule' => 'required|date_format:Y-m-d H:i:s',
            'comment' => 'nullable|string|max:255',
            'user_exercises' => 'required|array',
            'user_exercises.*.id' => 'required|integer|exists:user_exercises,id',
            'user_exercises.*.exercise_id' => 'required|integer|exists:exercises,id|distinct',
            'user_exercises.*.description' => 'required|string|max:255',
            'user_exercises.*.kilograms' => 'required|numeric|min:0',
            'user_exercises.*.set_count' => 'required|integer|min:1',
            'user_exercises.*.rep_count' => 'required|integer|min:1',
        ]);

        $workoutData = $validated;
        unset($workoutData['user_exercises']);

        DB::transaction(function () use ($workoutData, $workout, $request){
            $workout->update($workoutData);
            $exerciseIds = collect($request->user_exercises)->pluck('id')->filter()->toArray();
            UserExercise::where('workout_id', $workout->id)->whereNotIn('id', $exerciseIds)->delete();
            if ($request->has('user_exercises')) {
                foreach ($request->user_exercises as $userExercise) {
                    if (isset($userExercise['id'])) {
                        if (count($exerciseIds) > 1) {
                            UserExercise::where('id', $userExercise['id'])->update($userExercise);
                        }
                    } else {
                        $exerciseData = $workout->id;
                        UserExercise::create($exerciseData);
                    }
                }
            }
        });
        return response()->json(['message' => 'Workout updated successfully', 'workout' => $workout], 200);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Workout $workout)
    {
        if (Gate::denies('delete', $workout)) {
            return response()->json(['message' => 'Unauthorized'], 403);
        }
        DB::transaction(function () use ($workout){
            $workout->user_exercises()->delete();
            $workout->delete();
        });
        return response()->json(['message' => 'Workout deleted successfully'], 200);
    }
}
