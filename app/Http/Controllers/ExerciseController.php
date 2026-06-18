<?php

namespace App\Http\Controllers;

use App\Models\Exercise;
use Illuminate\Http\Request;

class ExerciseController extends Controller
{
    public function index(Request $request)
    {
        $search = $request->input('category');
        $exercises = Exercise::with('categories')
            ->when($search, function ($query) use ($search) {
                return $query->whereHas('categories', function($q) use ($search) {
                    $q->where('name', 'like', '%' . $search . '%');
                });
            })->get();
        return response()->json(['exercises' => $exercises], 200);
    }
}
