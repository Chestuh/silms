<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Course;
use Illuminate\Http\Request;

class CoursesController extends Controller
{
    public function index(Request $request)
    {
        $gradeLevels = ['7', '8', '9', '10', '11', '12'];

        $coursesQuery = Course::with('instructor.user')
            ->withCount(['learningMaterials', 'enrollments'])
            ->orderBy('grade_level')
            ->orderBy('code');

        if ($request->filled('grade_level') && in_array($request->grade_level, $gradeLevels, true)) {
            $coursesQuery->where('grade_level', $request->grade_level);
        }

        $courses = $coursesQuery->paginate(15)->withQueryString();

        return view('admin.courses.index', compact('courses', 'gradeLevels'));
    }

    public function create()
    {
        return view('admin.courses.create');
    }

    public function store(Request $request)
    {
        $rules = [
            'code' => ['required', 'string', 'max:20', 'unique:courses,code'],
            'title' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'in:7,8,9,10,11,12'],
            'units' => ['nullable', 'integer', 'min:1', 'max:10'],
            'semester' => ['nullable', 'in:1st Semester,2nd Semester'],
        ];

        if (in_array($request->grade_level, ['11', '12'])) {
            $rules['units'] = ['required', 'integer', 'min:1', 'max:10'];
            $rules['semester'] = ['required', 'in:1st Semester,2nd Semester'];
        }

        $validated = $request->validate($rules);

        if (!in_array($validated['grade_level'] ?? null, ['11', '12'])) {
            $validated['semester'] = null;
            unset($validated['units']);
        }

        Course::create($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course added.');
    }

    public function edit(Course $course)
    {
        return view('admin.courses.edit', compact('course'));
    }

    public function update(Request $request, Course $course)
    {
        $rules = [
            'code' => ['required', 'string', 'max:20', 'unique:courses,code,' . $course->id],
            'title' => ['required', 'string', 'max:255'],
            'grade_level' => ['nullable', 'in:7,8,9,10,11,12'],
            'units' => ['nullable', 'integer', 'min:1', 'max:10'],
            'semester' => ['nullable', 'in:1st Semester,2nd Semester'],
        ];

        if (in_array($request->grade_level, ['11', '12'])) {
            $rules['units'] = ['required', 'integer', 'min:1', 'max:10'];
            $rules['semester'] = ['required', 'in:1st Semester,2nd Semester'];
        }

        $validated = $request->validate($rules);

        if (!in_array($validated['grade_level'] ?? null, ['11', '12'])) {
            $validated['semester'] = null;
            unset($validated['units']);
        }

        $course->update($validated);

        return redirect()->route('admin.courses.index')->with('success', 'Course updated.');
    }

    public function destroy(Course $course)
    {
        if (method_exists($course, 'forceDelete')) {
            $course->forceDelete();
        } else {
            $course->delete();
        }

        return redirect()->route('admin.courses.index')->with('success', 'Course deleted.');
    }
}
