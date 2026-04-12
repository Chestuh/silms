<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class SelfAssessmentController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $assessments = $student->selfAssessments()->with('course')->orderByDesc('created_at')->get();
        return view('student.self-assessment', compact('assessments'));
    }
}
