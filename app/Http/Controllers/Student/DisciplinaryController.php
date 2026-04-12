<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class DisciplinaryController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }
        $records = $student->disciplinaryRecords()->orderByDesc('incident_date')->get();
        return view('student.disciplinary', compact('records'));
    }
}
