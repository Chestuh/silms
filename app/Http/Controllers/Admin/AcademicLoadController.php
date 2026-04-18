<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use App\Models\Course;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicLoadController extends Controller
{
    /**
     * Validate subject loads for irregular or transferee students to avoid overload or conflicts.
     */
    public function index(Request $request)
    {
        $maxUnits = (float) ($request->get('max_units') ?: 24);
        $semester = $request->get('semester');
        $schoolYear = $request->get('school_year');

        $query = Enrollment::with(['student.user', 'course'])
            ->where('status', 'enrolled')
            ->whereHas('student', function ($q) {
                $q->whereIn('year_level', [11, 12]);
            });

        if ($semester) {
            $query->where('semester', $semester);
        }
        if ($schoolYear) {
            $query->where('school_year', $schoolYear);
        }

        $enrollments = $query->get();
        $byStudent = $enrollments->groupBy('student_id');

        $overloads = [];
        foreach ($byStudent as $studentId => $items) {
            $totalUnits = $items->sum(fn ($e) => (float) ($e->course->units ?? 0));
            if ($totalUnits > $maxUnits) {
                $student = $items->first()->student;
                $overloads[] = [
                    'student' => $student,
                    'total_units' => $totalUnits,
                    'max_units' => $maxUnits,
                    'enrollments' => $items,
                ];
            }
        }

        $semesters = Enrollment::whereNotNull('semester')->distinct()->pluck('semester');
        $schoolYears = Enrollment::whereNotNull('school_year')->distinct()->pluck('school_year');

        return view('admin.academic-load.index', compact('overloads', 'maxUnits', 'semesters', 'schoolYears', 'semester', 'schoolYear'));
    }
}
