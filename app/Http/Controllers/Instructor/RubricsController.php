<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use App\Models\Rubric;
use Illuminate\Http\Request;

class RubricsController extends Controller
{
    protected function authorizeInstructor(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        return $instructor;
    }

    protected function authorizeRubric(Request $request, Rubric $rubric)
    {
        $instructor = $this->authorizeInstructor($request);
        if (!$rubric->course || $rubric->course->instructor_id !== $instructor->id) {
            abort(403);
        }
        return $rubric;
    }

    public function index(Request $request)
    {
        $instructor = $this->authorizeInstructor($request);
        $courseIds = $instructor->courses()->pluck('id');
        $rubrics = Rubric::whereIn('course_id', $courseIds)
            ->with(['course:id,code,title,instructor_id', 'material:id,title,course_id'])
            ->latest()
            ->paginate(15);
        return view('instructor.rubrics.index', compact('rubrics'));
    }

    public function create(Request $request)
    {
        $instructor = $this->authorizeInstructor($request);
        $courses = $instructor->courses()->orderBy('code')->get();
        $materials = LearningMaterial::whereIn('course_id', $courses->pluck('id'))
            ->with('course:id,code')
            ->orderBy('course_id')
            ->orderBy('order_index')
            ->get();
        return view('instructor.rubrics.create', compact('courses', 'materials'));
    }

    public function store(Request $request)
    {
        $instructor = $this->authorizeInstructor($request);
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'material_id' => ['nullable', 'exists:learning_materials,id'],
            'name' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'array', 'min:1'],
            'criteria.*.description' => ['required', 'string', 'max:255'],
            'criteria.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $courseIds = $instructor->courses()->pluck('id')->toArray();
        if (!in_array((int)$validated['course_id'], $courseIds, true)) {
            abort(403);
        }

        if (!empty($validated['material_id'])) {
            $material = LearningMaterial::find($validated['material_id']);
            if (!$material || $material->course_id !== (int)$validated['course_id']) {
                abort(403);
            }
        }

        $criteria = collect($validated['criteria'])->map(function ($item) {
            return [
                'description' => trim($item['description']),
                'weight' => (int)$item['weight'],
            ];
        })->filter(function ($item) {
            return $item['description'] !== '';
        })->values()->all();

        if (empty($criteria)) {
            return back()->withInput()->withErrors(['criteria' => 'Please provide at least one rubric criterion.']);
        }

        Rubric::create([
            'course_id' => $validated['course_id'],
            'material_id' => $validated['material_id'] ?? null,
            'name' => $validated['name'],
            'criteria_json' => $criteria,
        ]);

        return redirect()->route('instructor.rubrics.index')->with('success', 'Rubric created.');
    }

    public function show(Request $request, Rubric $rubric)
    {
        $this->authorizeRubric($request, $rubric);
        $rubric->load(['course:id,code,title,instructor_id', 'material:id,title']);
        return view('instructor.rubrics.show', compact('rubric'));
    }

    public function edit(Request $request, Rubric $rubric)
    {
        $this->authorizeRubric($request, $rubric);
        $instructor = $this->authorizeInstructor($request);
        $courses = $instructor->courses()->orderBy('code')->get();
        $materials = LearningMaterial::whereIn('course_id', $courses->pluck('id'))
            ->with('course:id,code')
            ->orderBy('course_id')
            ->orderBy('order_index')
            ->get();
        $rubric->load(['course:id,code,title,instructor_id', 'material:id,title,course_id']);
        return view('instructor.rubrics.edit', compact('rubric', 'courses', 'materials'));
    }

    public function update(Request $request, Rubric $rubric)
    {
        $this->authorizeRubric($request, $rubric);
        $validated = $request->validate([
            'course_id' => ['required', 'exists:courses,id'],
            'material_id' => ['nullable', 'exists:learning_materials,id'],
            'name' => ['required', 'string', 'max:255'],
            'criteria' => ['required', 'array', 'min:1'],
            'criteria.*.description' => ['required', 'string', 'max:255'],
            'criteria.*.weight' => ['required', 'numeric', 'min:0', 'max:100'],
        ]);

        $instructorCourseIds = $request->user()->instructor->courses()->pluck('id')->toArray();
        if (!in_array((int)$validated['course_id'], $instructorCourseIds, true)) {
            abort(403);
        }

        if (!empty($validated['material_id'])) {
            $material = LearningMaterial::find($validated['material_id']);
            if (!$material || $material->course_id !== (int)$validated['course_id']) {
                abort(403);
            }
        }

        $criteria = collect($validated['criteria'])->map(function ($item) {
            return [
                'description' => trim($item['description']),
                'weight' => (int)$item['weight'],
            ];
        })->filter(function ($item) {
            return $item['description'] !== '';
        })->values()->all();

        if (empty($criteria)) {
            return back()->withInput()->withErrors(['criteria' => 'Please provide at least one rubric criterion.']);
        }

        $rubric->update([
            'course_id' => $validated['course_id'],
            'material_id' => $validated['material_id'] ?? null,
            'name' => $validated['name'],
            'criteria_json' => $criteria,
        ]);

        return redirect()->route('instructor.rubrics.show', $rubric)->with('success', 'Rubric updated.');
    }

    public function destroy(Request $request, Rubric $rubric)
    {
        $this->authorizeRubric($request, $rubric);
        $rubric->delete();
        return redirect()->route('instructor.rubrics.index')->with('success', 'Rubric deleted.');
    }
}
