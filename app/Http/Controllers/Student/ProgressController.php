<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $progress = $student->learningProgress()->with('material.course')->orderByDesc('completed_at')->orderByDesc('progress_percent')->get();
        $totalTime = $progress->sum('time_spent_minutes');
        $completed = $progress->where('progress_percent', 100)->count();

        return view('student.progress', compact('progress', 'totalTime', 'completed'));
    }
}
