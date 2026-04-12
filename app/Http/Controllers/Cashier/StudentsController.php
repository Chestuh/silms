<?php

namespace App\Http\Controllers\Cashier;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('user')->orderBy('student_number')->paginate(15);
        return view('cashier.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load(['user', 'enrollments.course', 'fees']);
        return view('cashier.students.show', compact('student'));
    }
}
