<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\AcademicHonor;
use Illuminate\Http\Request;

class HonorsController extends Controller
{
    /**
     * Academic honors: identify students eligible based on academic performance rules.
     */
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $courseIds = $instructor->courses()->pluck('id');
        $studentIds = \App\Models\Enrollment::whereIn('course_id', $courseIds)
            ->whereIn('status', ['enrolled', 'completed'])
            ->distinct()
            ->pluck('student_id');
        $students = Student::with(['user', 'academicHonors'])
            ->whereIn('id', $studentIds)
            ->orderBy('student_number')
            ->get()
            ->map(function ($student) {
                $student->gwa = $student->computeGwa();
                return $student;
            });
        // Sort by GWA desc for honors list (e.g. 1.0–1.25 with honors)
        $students = $students->sortByDesc(function ($s) {
            return $s->gwa ?? 0;
        })->values();
        return view('instructor.honors.index', compact('students'));
    }
}
