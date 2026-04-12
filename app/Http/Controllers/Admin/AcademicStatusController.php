<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class AcademicStatusController extends Controller
{
    /**
     * Monitor student academic status: passed, failed, inc, drop.
     */
    public function index(Request $request)
    {
        $filter = $request->get('status');
        $students = Student::with('user')
            ->orderBy('student_number')
            ->get()
            ->map(function ($student) {
                $student->resolved_academic_status = $student->getResolvedAcademicStatus();
                $student->gwa = $student->computeGwa();
                return $student;
            });

        if ($filter && in_array($filter, ['passed', 'failed', 'inc', 'drop'], true)) {
            $students = $students->where('resolved_academic_status', $filter)->values();
        }

        $counts = [
            'passed' => Student::all()->filter(fn ($s) => $s->getResolvedAcademicStatus() === 'passed')->count(),
            'failed' => Student::all()->filter(fn ($s) => $s->getResolvedAcademicStatus() === 'failed')->count(),
            'inc' => Student::all()->filter(fn ($s) => $s->getResolvedAcademicStatus() === 'inc')->count(),
            'drop' => Student::all()->filter(fn ($s) => $s->getResolvedAcademicStatus() === 'drop')->count(),
        ];

        return view('admin.academic-status.index', compact('students', 'counts', 'filter'));
    }
}
