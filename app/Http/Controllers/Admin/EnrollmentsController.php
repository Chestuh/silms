<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class EnrollmentsController extends Controller
{
    public function index(Request $request)
    {
        $enrollments = Enrollment::with(['student.user', 'course'])
            ->orderByDesc('created_at')
            ->paginate(15);
        return view('admin.enrollments.index', compact('enrollments'));
    }

    public function show(Enrollment $enrollment)
    {
        $enrollment->load(['student.user', 'course']);
        return view('admin.enrollments.show', compact('enrollment'));
    }

    public function approve(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'enrolled']);
        
        // Auto-credit all courses in the same grade/department when approved
        $enrollment->autoCreditDepartmentCourses();
        
        // Ensure the student has a student_number when their enrollment is approved
        $student = $enrollment->student;
        if ($student) {
            $student->ensureStudentNumber();
        }
        
        return back()->with('success', 'Enrollment approved.');
    }

    public function reject(Enrollment $enrollment)
    {
        $enrollment->update(['status' => 'dropped']);
        return back()->with('success', 'Enrollment rejected.');
    }
}
