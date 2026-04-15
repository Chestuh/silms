<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;

class MaterialsController extends Controller
{
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }

        $activeTab = $request->query('tab', 'all');
        $materialsQuery = LearningMaterial::whereIn('course_id', $instructor->courses()->pluck('id'))
            ->with('course:id,code,title')
            ->orderBy('course_id')
            ->orderBy('order_index');

        if ($activeTab === 'archived') {
            $materialsQuery->where('archived', true);
        } else {
            $materialsQuery->where('archived', false);
        }

        $materials = $materialsQuery->get();

        return view('instructor.materials.index', compact('materials', 'activeTab'));
    }

    public function create(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $courses = $instructor->courses()->orderBy('code')->get();
        return view('instructor.materials.create', compact('courses'));
    }

    public function store(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'format' => ['required', 'string', 'in:document,pdf,video,link,quiz'],
            'file' => ['required_if:format,document,pdf,video', 'file', 'mimes:docx,pdf,mp4', 'max:20480'],
            'url' => ['nullable', 'url', 'max:500', 'required_if:format,link,quiz'],
            'difficulty_level' => ['nullable', 'in:easy,medium,hard'],
        ]);

        if (!in_array((int) $validated['course_id'], $instructor->courses()->pluck('id')->toArray())) {
            abort(403);
        }

        if (in_array($validated['format'], ['document', 'pdf', 'video'])) {
            $file = $request->file('file');
            if ($file) {
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $validated['file_path'] = $fileName;
            }
            $validated['url'] = null;
        } else {
            $validated['file_path'] = null;
        }

        $validated['order_index'] = LearningMaterial::where('course_id', $validated['course_id'])->max('order_index') + 1;
        $validated['difficulty_level'] = $validated['difficulty_level'] ?? 'medium';

        LearningMaterial::create($validated);

        return redirect()->route('instructor.materials.index')->with('success', 'Learning material added.');
    }

    public function edit(Request $request, LearningMaterial $material)
    {
        $this->authorizeMaterial($request, $material);
        $instructor = $request->user()->instructor;
        $courses = $instructor->courses()->orderBy('code')->get();
        return view('instructor.materials.create', compact('courses', 'material'));
    }

    public function update(Request $request, LearningMaterial $material)
    {
        $this->authorizeMaterial($request, $material);

        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'title' => ['required', 'string', 'max:255'],
            'description' => ['nullable', 'string'],
            'format' => ['required', 'string', 'in:document,pdf,video,link,quiz'],
            'file' => ['nullable', 'file', 'mimes:docx,pdf,mp4', 'max:20480'],
            'url' => ['nullable', 'url', 'max:500', 'required_if:format,link,quiz'],
            'difficulty_level' => ['nullable', 'in:easy,medium,hard'],
        ]);

        $instructorCourseIds = $request->user()->instructor->courses()->pluck('id')->toArray();
        if (!in_array((int) $validated['course_id'], $instructorCourseIds, true)) {
            abort(403);
        }

        if (in_array($validated['format'], ['document', 'pdf', 'video'])) {
            if (!$material->file_path && !$request->hasFile('file')) {
                return back()->withInput()->withErrors(['file' => 'Please upload a file for this format.']);
            }

            if ($request->hasFile('file')) {
                if ($material->file_path && file_exists(public_path('uploads/' . $material->file_path))) {
                    unlink(public_path('uploads/' . $material->file_path));
                }
                $file = $request->file('file');
                $fileName = time() . '_' . uniqid() . '.' . $file->getClientOriginalExtension();
                $file->move(public_path('uploads'), $fileName);
                $validated['file_path'] = $fileName;
            } else {
                $validated['file_path'] = $material->file_path;
            }

            $validated['url'] = null;
        } else {
            if ($material->file_path && file_exists(public_path('uploads/' . $material->file_path))) {
                unlink(public_path('uploads/' . $material->file_path));
            }
            $validated['file_path'] = null;
        }

        $material->update([
            'course_id' => $validated['course_id'],
            'title' => $validated['title'],
            'description' => $validated['description'] ?? null,
            'format' => $validated['format'],
            'file_path' => $validated['file_path'],
            'url' => $validated['url'] ?? null,
            'difficulty_level' => $validated['difficulty_level'] ?? 'medium',
        ]);

        return redirect()->route('instructor.materials.index')->with('success', 'Learning material updated.');
    }

    public function destroy(Request $request, LearningMaterial $material)
    {
        $this->authorizeMaterial($request, $material);

        if ($material->file_path && file_exists(public_path('uploads/' . $material->file_path))) {
            unlink(public_path('uploads/' . $material->file_path));
        }

        $material->delete();

        return redirect()->route('instructor.materials.index')->with('success', 'Learning material removed.');
    }

    public function archive(Request $request, LearningMaterial $material)
    {
        $this->authorizeMaterial($request, $material);

        $material->update(['archived' => true]);

        return back()->with('success', 'Learning material archived.');
    }

    public function unarchive(Request $request, LearningMaterial $material)
    {
        $this->authorizeMaterial($request, $material);

        $material->update(['archived' => false]);

        return back()->with('success', 'Learning material restored from archive.');
    }

    private function authorizeInstructor(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        return $instructor;
    }

    private function authorizeMaterial(Request $request, LearningMaterial $material)
    {
        $instructor = $this->authorizeInstructor($request);
        if (!in_array($material->course_id, $instructor->courses()->pluck('id')->toArray(), true)) {
            abort(403);
        }
        return $material;
    }
}
