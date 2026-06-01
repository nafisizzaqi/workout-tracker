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

        $all_workouts = Workout::where('user_id', $request->user()->id)->whereBetween('schedule', [$validated['start_date'], $validated['end_date']])->get();
        $data = UserExercise::selectRaw('count(*) AS total_exercise')->whereIn('workout_id', $all_workouts->pluck('id')->toArray())->first();
        $data_missed = Workout::selectRaw('count(*) AS total_missed')->where('user_id', $request->user()->id)->whereBetween('schedule', [$validated['start_date'], $validated['end_date']])->where('status', 'missed')->first();
        $workout_done = $all_workouts->where('status', 'done');
        $data_done = UserExercise::selectRaw('sum(rep_count) AS total_rep, sum(set_count) AS total_set, count(*) AS done')->whereIn('workout_id', $workout_done->pluck('id')->toArray())->first();

        $return_data = [
            'total_workouts' => $all_workouts->count(),
            'workout_missed' => (int)$data_missed->total_missed ?? 0,
            'workout_done' => $workout_done->count(),
            'total_exercise' => (int)$data->total_exercise ?? 0,   
            'exercise_done' => (int)$data_done->done ?? 0,
            'sets_done' => (int)$data_done->total_set ?? 0,
            'reps_done' => (int)$data_done->total_rep ?? 0,
        ];

        return response()->json(['data' => $return_data], 200);
    }
}
