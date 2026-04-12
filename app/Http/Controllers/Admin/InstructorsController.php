<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Instructor;
use App\Models\User;
use App\Models\Course;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Validation\Rules\Password;

class InstructorsController extends Controller
{
    public function index(Request $request)
    {
        $department = $request->query('department');
        
        $instructorsQuery = Instructor::with('user')->orderBy('id');
        
        // Filter by department if specified
        if ($department) {
            $instructorsQuery->where('department', $department);
        }
        
        $instructors = $instructorsQuery->withCount('courses')->paginate(15);
        $departments = ['7', '8', '9', '10', '11', '12'];
        
        // Load courses for the selected department
        $courses = Course::where('grade_level', $department)->orderBy('code')->get();
        
        return view('admin.instructors.index', compact('instructors', 'departments', 'department', 'courses'));
    }

    public function create(Request $request)
    {
        $courses = Course::orderBy('code')->get();
        return view('admin.instructors.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email'],
            'password' => ['required', 'confirmed', Password::defaults()],
            'department' => ['nullable', 'string', 'max:100'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $user = User::create([
            'name' => $validated['name'],
            'email' => $validated['email'],
            'password' => Hash::make($validated['password']),
            'role' => 'instructor',
        ]);

        $instructor = Instructor::create([
            'user_id' => $user->id,
            'department' => $validated['department'] ?? null,
        ]);

        if (!empty($validated['course_ids'])) {
            Course::whereIn('id', $validated['course_ids'])->update(['instructor_id' => $instructor->id]);
        }

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor added.');
    }

    public function edit(Instructor $instructor)
    {
        $instructor->load('user');
        $courses = Course::orderBy('code')->get();
        return view('admin.instructors.edit', compact('instructor', 'courses'));
    }

    public function update(Request $request, Instructor $instructor)
    {
        $validated = $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users,email,' . $instructor->user_id],
            'department' => ['nullable', 'string', 'max:100'],
            'course_ids' => ['nullable', 'array'],
            'course_ids.*' => ['exists:courses,id'],
        ]);

        $instructor->user->update([
            'name' => $validated['name'],
            'email' => $validated['email'],
        ]);
        $instructor->update(['department' => $validated['department'] ?? null]);

        Course::where('instructor_id', $instructor->id)->update(['instructor_id' => null]);
        if (!empty($validated['course_ids'])) {
            Course::whereIn('id', $validated['course_ids'])->update(['instructor_id' => $instructor->id]);
        }

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor updated.');
    }

    /**
     * Remove the specified instructor and its user account.
     */
    public function destroy(Instructor $instructor)
    {
        // Unassign courses
        Course::where('instructor_id', $instructor->id)->update(['instructor_id' => null]);

        // Delete instructor and associated user
        $user = $instructor->user;
        $instructor->delete();
        if ($user) {
            $user->delete();
        }

        return redirect()->route('admin.instructors.index')->with('success', 'Instructor deleted.');
    }
}
