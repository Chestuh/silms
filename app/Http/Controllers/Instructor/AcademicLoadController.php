<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class AcademicLoadController extends Controller
{
    /**
     * Monitor academic load for students assigned to this instructor.
     *
     * This view helps identify overloads for senior high / irregular students
     * based on the selected semester and school year.
     */
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (! $instructor) {
            abort(403);
        }

        $maxUnits = (float) ($request->get('max_units') ?: config('education.max_units_per_semester', 24));
        $semester = $request->get('semester');
        $schoolYear = $request->get('school_year');

        $studentIds = Enrollment::where('status', 'enrolled')
            ->whereHas('course', function ($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })
            ->when($semester, fn ($q) => $q->where('semester', $semester))
            ->when($schoolYear, fn ($q) => $q->where('school_year', $schoolYear))
            ->distinct()
            ->pluck('student_id');

        $enrollments = Enrollment::with(['student.user', 'course'])
            ->where('status', 'enrolled')
            ->whereIn('student_id', $studentIds)
            ->when($semester, fn ($q) => $q->where('semester', $semester))
            ->when($schoolYear, fn ($q) => $q->where('school_year', $schoolYear))
            ->get();

        $byStudent = $enrollments->groupBy('student_id');
        $overloads = [];

        foreach ($byStudent as $items) {
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

        $semesters = Enrollment::whereHas('course', function ($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })
            ->whereNotNull('semester')
            ->distinct()
            ->pluck('semester');

        $schoolYears = Enrollment::whereHas('course', function ($q) use ($instructor) {
                $q->where('instructor_id', $instructor->id);
            })
            ->whereNotNull('school_year')
            ->distinct()
            ->pluck('school_year');

        return view('instructor.academic-load.index', compact(
            'overloads',
            'maxUnits',
            'semesters',
            'schoolYears',
            'semester',
            'schoolYear'
        ));
    }
}
