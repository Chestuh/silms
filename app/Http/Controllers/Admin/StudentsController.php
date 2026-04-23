<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use Illuminate\Http\Request;

class StudentsController extends Controller
{
    public function index(Request $request)
    {
        $search = trim($request->query('search', ''));

        $students = Student::with('user')
            ->when($search, function ($query) use ($search) {
                $query->where('student_number', 'like', "%{$search}%")
                    ->orWhereHas('user', function ($query) use ($search) {
                        $query->where('name', 'like', "%{$search}%");
                    });
            })
            ->orderBy('student_number')
            ->paginate(15)
            ->withQueryString();

        return view('admin.students.index', compact('students'));
    }

    public function show(Student $student)
    {
        $student->load(['user', 'enrollments.course', 'enrollments.grade']);
        $gwa = $student->computeGwa();
        $academicStatus = $student->getResolvedAcademicStatus();
        $enrolledUnits = $student->enrollments->sum(fn ($enrollment) => $enrollment->course->units ?? 0);
        return view('admin.students.show', compact('student', 'gwa', 'academicStatus', 'enrolledUnits'));
    }

    public function edit(Student $student)
    {
        $student->load('user');
        return view('admin.students.edit', compact('student'));
    }

    public function update(Request $request, Student $student)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|max:255|unique:users,email,' . $student->user_id,
        ]);
        $student->user->update([
            'name' => $request->name,
            'email' => $request->email,
        ]);
        return redirect()
            ->route('admin.students.show', $student)
            ->with('success', 'Student updated.');
    }
}
