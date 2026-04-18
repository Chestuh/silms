<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AcademicStatusController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }
        $enrollments = $student->enrollments()->with('course')->orderByDesc('school_year')->orderBy('semester')->get();
        $totalUnits = $enrollments->where('status', 'enrolled')->sum(fn ($e) => (float) ($e->course->units ?? 0));
        return view('student.academic-status', compact('student', 'enrollments', 'totalUnits'));
    }
}
