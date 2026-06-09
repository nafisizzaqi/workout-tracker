<?php

namespace App\Http\Controllers;

use App\Models\Workout;
use App\Models\UserExercise;
use Illuminate\Http\Request;

class ReportController extends Controller
{
    public function index(Request $request)
    {   
        $validated = $request->validate(
            ['end_date' => 'required|date_format:Y-m-d', 'start_date' => 'required|date_format:Y-m-d'],
        );

        $allWorkouts = Workout::where('user_id', $request->user()->id)
            ->whereBetween('schedule', [$validated['start_date'], $validated['end_date']])
            ->get();
        $dataUserExercise = UserExercise::selectRaw('count(*) AS total_exercise')
            ->whereIn('workout_id', $allWorkouts->pluck('id')
            ->toArray())
            ->first();
        $dataWorkoutMiss = Workout::selectRaw('count(*) AS total_missed')
            ->where('user_id', $request->user()->id)
            ->whereBetween('schedule', [$validated['start_date'], $validated['end_date']])
            ->where('status', 'missed')
            ->first();
        $dataWorkoutDone = $allWorkouts->where('status', 'done');
        $allDataDone = UserExercise::selectRaw('sum(rep_count) AS total_rep, sum(set_count) AS total_set, count(*) AS done')
            ->whereIn('workout_id', $dataWorkoutDone->pluck('id')
            ->toArray())
            ->first();

        $return_data = [
            'total_workouts' => $allWorkouts->count(),
            'workout_missed' => (int)$dataWorkoutMiss->total_missed ?? 0,
            'workout_done' => $dataWorkoutDone->count(),
            'total_exercise' => (int)$dataUserExercise->total_exercise ?? 0,   
            'exercise_done' => (int)$allDataDone->done ?? 0,
            'sets_done' => (int)$allDataDone->total_set ?? 0,
            'reps_done' => (int)$allDataDone->total_rep ?? 0,
        ];

        return response()->json(['data' => $return_data], 200);
    }
}
