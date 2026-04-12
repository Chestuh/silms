<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Grade;
use App\Models\Rubric;
use Illuminate\Http\Request;

class GradesController extends Controller
{
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $courses = $instructor->courses()->withCount('enrollments')->orderBy('code')->get();
        return view('instructor.grades.index', compact('courses'));
    }

    public function show(Request $request, Course $course)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor || $course->instructor_id !== $instructor->id) {
            abort(403);
        }
        $rubrics = Rubric::where('course_id', $course->id)
            ->with('material:id,title')
            ->orderBy('name')
            ->get();
        $enrollments = $course->enrollments()
            ->with(['student.user', 'grade.rubric'])
            ->where('status', 'enrolled')
            ->get()
            ->sortBy(fn ($e) => $e->student->user->name ?? $e->student->student_number ?? '')
            ->values();

        $selectedRubricId = old('rubric_id');
        if ($selectedRubricId === null && $enrollments->isNotEmpty()) {
            $selectedRubricId = optional($enrollments->first()->grade)->rubric_id;
        }

        return view('instructor.grades.show', compact('course', 'enrollments', 'rubrics', 'selectedRubricId'));
    }

    public function update(Request $request, Course $course)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor || $course->instructor_id !== $instructor->id) {
            abort(403);
        }
        $valid = $request->validate([
            'rubric_id' => ['nullable', 'exists:rubrics,id'],
            'grades' => 'required|array',
            'grades.*.enrollment_id' => 'required|exists:enrollments,id',
            'grades.*.midterm_grade' => 'nullable|integer|min:0|max:100',
            'grades.*.final_grade' => 'nullable|integer|min:0|max:100',
        ]);

        if (!empty($valid['rubric_id'])) {
            $rubric = Rubric::find($valid['rubric_id']);
            if (!$rubric || $rubric->course_id !== $course->id) {
                abort(403);
            }
        }

        foreach ($valid['grades'] as $row) {
            $enrollment = Enrollment::find($row['enrollment_id']);
            if (!$enrollment || $enrollment->course_id != $course->id) {
                continue;
            }
            $grade = $enrollment->grade()->firstOrCreate([], [
                'midterm_grade' => null,
                'final_grade' => null,
                'gwa_contribution' => null,
                'rubric_id' => $valid['rubric_id'] ?? null,
            ]);
            $mid = array_key_exists('midterm_grade', $row) ? ($row['midterm_grade'] === '' || $row['midterm_grade'] === null ? null : (int) $row['midterm_grade']) : $grade->midterm_grade;
            $fin = array_key_exists('final_grade', $row) ? ($row['final_grade'] === '' || $row['final_grade'] === null ? null : (int) $row['final_grade']) : $grade->final_grade;
            $grade->update([
                'midterm_grade' => $mid,
                'final_grade' => $fin,
                'rubric_id' => $valid['rubric_id'] ?? null,
            ]);
        }
        return redirect()
            ->route('instructor.grades.show', $course)
            ->with('success', 'Grades updated.');
    }
}
