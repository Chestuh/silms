<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class AdmissionController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }
        $records = $student->admissionRecords()->orderByDesc('date_processed')->get();
        return view('student.admission', compact('records'));
    }
}
