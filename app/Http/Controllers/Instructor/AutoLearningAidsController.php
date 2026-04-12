<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\AutoLearningAid;
use App\Models\Course;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class AutoLearningAidsController extends Controller
{
    public function index(Request $request)
    {
        $instructor = $request->user();

        $aids = AutoLearningAid::with(['material.course', 'instructor'])
            ->whereHas('material.course', function ($query) use ($instructor) {
                $query->where('instructor_id', $instructor->id);
            })
            ->orderBy('release_at', 'desc')
            ->get();

        return view('instructor.learning-aids.index', compact('aids'));
    }

    public function create(Request $request)
    {
        $instructor = $request->user();

        $courses = Course::where('instructor_id', $instructor->id)->get();

        $materials = LearningMaterial::whereIn('course_id', $courses->pluck('id'))->get();

        return view('instructor.learning-aids.create', compact('courses', 'materials'));
    }

    public function store(Request $request)
    {
        $instructor = $request->user();

        $data = $request->validate([
            'material_id' => 'required|exists:learning_materials,id',
            'title' => 'required|string|max:255',
            'description' => 'nullable|string|max:1000',
            'release_at' => 'required|date|after_or_equal:today',
            'files.*' => 'required|file|max:10240',
        ]);

        $material = LearningMaterial::findOrFail($data['material_id']);
        if ($material->course && $material->course->instructor_id !== $instructor->id) {
            abort(403);
        }

        $files = $request->file('files');

        foreach ($files as $file) {
            $path = $file->store('learning_aids', 'public');

            AutoLearningAid::create([
                'material_id' => $material->id,
                'instructor_id' => $instructor->id,
                'course_id' => $material->course_id,
                'title' => $data['title'],
                'description' => $data['description'] ?? null,
                'file_path' => $path,
                'release_at' => $data['release_at'],
                'status' => 'scheduled',
            ]);
        }

        return redirect()->route('instructor.learning-aids.index')->with('success', 'Auto-generated learning aids scheduled successfully.');
    }
}
