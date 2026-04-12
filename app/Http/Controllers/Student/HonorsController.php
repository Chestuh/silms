<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class HonorsController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }
        $honors = $student->academicHonors()->orderByDesc('school_year')->orderBy('semester')->get();
        return view('student.honors', compact('honors'));
    }
}
