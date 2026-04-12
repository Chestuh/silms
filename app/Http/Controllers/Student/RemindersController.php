<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\StudyReminder;
use Illuminate\Http\Request;

class RemindersController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $reminders = $student->studyReminders()->with('material')->orderBy('remind_at')->get();
        $materials = LearningMaterial::where('archived', false)->orderBy('title')->get(['id', 'title']);

        return view('student.reminders', compact('reminders', 'materials'));
    }

    public function store(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $data = $request->validate([
            'title' => ['required', 'string', 'max:255'],
            'material_id' => ['nullable', 'exists:learning_materials,id'],
            'remind_at' => ['required', 'date'],
        ]);

        StudyReminder::create([
            'student_id' => $student->id,
            'material_id' => $data['material_id'] ?? null,
            'title' => $data['title'],
            'remind_at' => $data['remind_at'],
        ]);

        return redirect()->route('student.reminders.index')->with('success', 'Reminder added.');
    }

    public function destroy(Request $request, StudyReminder $reminder)
    {
        $student = $request->user()->student;
        if (!$student || $reminder->student_id !== $student->id) abort(403);

        $reminder->delete();
        return redirect()->route('student.reminders.index');
    }
}
