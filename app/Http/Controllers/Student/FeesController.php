<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;

class FeesController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (! $student) {
            abort(403);
        }
        $fees = $student->fees()->orderBy('due_date')->orderBy('status')->get();
        return view('student.fees', compact('fees'));
    }
}
