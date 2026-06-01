<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Workout;
use Illuminate\Validation\Rule;
use App\Models\UserExercise;
use Illuminate\Support\Facades\DB;
class WorkoutController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $workouts = Workout::where('user_id', $request->user()->id)->with('user_exercises')->get();
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
            'user_exercises' => 'required|array',
            'user_exercises.*.exercise_id' => 'required|integer|exists:exercises,id|distinct',
            'user_exercises.*.description' => 'required|string|max:255',
            'user_exercises.*.kilograms' => 'required|numeric|min:0',
            'user_exercises.*.set_count' => 'required|integer|min:1',
            'user_exercises.*.rep_count' => 'required|integer|min:1',
            'status' => [Rule::in(['done', 'pending', 'missed']), 'nullable'],
        ]);

        $workout = $validated;
        unset($workout['user_exercises']);
        $workout['user_id'] = $request->user()->id;
        $workout = Workout::create($workout);
        $now = now();
        $user_exercises_data = [];
        for($i = 0; $i < count($request->user_exercises); $i++){
            $user_exercises_data[] = $request->user_exercises[$i];
            $user_exercises_data[$i]['workout_id'] = $workout->id;
            $user_exercises_data[$i]['created_at'] = $now;
            $user_exercises_data[$i]['updated_at'] = $now;
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
        $validated = $request->validate([
            'name' => 'required|string|max:255',
            'status' => [Rule::in(['done', 'pending', 'missed']), 'nullable'],
            'schedule' => 'required|date_format:Y-m-d H:i:s',
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
        $deleted = $workout->delete();
        if (!$deleted) {
            return response()->json(['message' => 'Failed to delete workout'], 500);
        }
        return response()->json(['message' => 'Workout deleted successfully', 'workout' => $workout], 200);
    }
}
