<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicStatusController extends Controller
{
    /**
     * Monitor academic status of students in instructor's courses.
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
                $student->resolved_academic_status = $student->getResolvedAcademicStatus();
                $student->gwa = $student->computeGwa();
                return $student;
            });
        $filter = $request->get('status');
        if ($filter && in_array($filter, ['passed', 'failed', 'inc', 'drop'], true)) {
            $students = $students->where('resolved_academic_status', $filter)->values();
        }
        return view('instructor.academic-status.index', compact('students', 'filter'));
    }
}
