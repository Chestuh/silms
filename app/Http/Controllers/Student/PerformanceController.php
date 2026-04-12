<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class PerformanceController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $progressRecords = $student->learningProgress()->with('material.course')->get();
        $activities = $progressRecords->count();
        $totalTime = $progressRecords->sum('time_spent_minutes');
        $completed = $progressRecords->where('progress_percent', 100)->count();
        $completionPercent = $activities > 0 ? round(100 * $completed / $activities, 1) : 0;

        $avgGrade = $student->enrollments()
            ->with('grade')
            ->get()
            ->filter(fn ($e) => $e->grade && $e->grade->midterm_grade !== null && $e->grade->final_grade !== null)
            ->map(fn ($e) => ($e->grade->midterm_grade + $e->grade->final_grade) / 2)
            ->avg();

        // Skill gaps: materials with low progress or not started
        $gaps = $progressRecords->filter(fn ($p) => $p->progress_percent < 100)
            ->sortBy('progress_percent')
            ->take(5)
            ->values();

        return view('student.performance', compact('activities', 'totalTime', 'avgGrade', 'completionPercent', 'completed', 'gaps'));
    }
}
