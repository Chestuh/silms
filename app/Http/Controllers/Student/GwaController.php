<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class GwaController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $grades = $student->enrollments()
            ->with(['course', 'grade.rubric'])
            ->whereIn('status', ['enrolled', 'completed'])
            ->orderByDesc('school_year')
            ->orderByDesc('semester')
            ->get();

        $totalUnits = 0;
        $weightedSum = 0;
        foreach ($grades as $e) {
            $g = $e->grade;
            if (!$g || $g->midterm_grade === null || $g->final_grade === null) continue;
            $avg = ($g->midterm_grade + $g->final_grade) / 2;
            $u = (float) $e->course->units;
            $totalUnits += $u;
            $weightedSum += $avg * $u;
        }
        $gwa = $totalUnits > 0 ? $weightedSum / $totalUnits : null;

        return view('student.gwa', compact('grades', 'gwa'));
    }
}
