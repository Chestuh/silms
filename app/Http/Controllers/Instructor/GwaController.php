<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class GwaController extends Controller
{
    /**
     * View students' GWA (for courses taught by this instructor).
     */
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $courseIds = $instructor->courses()->pluck('id');
        $studentIds = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->where('status', 'enrolled')
            ->distinct()
            ->pluck('student_id');
        $students = Student::with('user')
            ->whereIn('id', $studentIds)
            ->orderBy('student_number')
            ->get()
            ->map(function ($student) {
                $student->gwa = $student->computeGwa();
                return $student;
            });
        return view('instructor.gwa.index', compact('students'));
    }
}
