<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\LearningPathRule;
use App\Models\Course;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    public function index(Request $request)
    {
        $recommendationEnabled = config('app.learning_path_recommendation_enabled', true);
        $rules = LearningPathRule::with(['sourceCourse', 'targetCourse', 'sourceMaterial', 'targetMaterial'])
            ->orderBy('sort_order')
            ->orderBy('id')
            ->get();
        $courses = Course::orderBy('code')->get();
        $materials = LearningMaterial::with('course:id,code,title')->orderBy('course_id')->orderBy('order_index')->get();
        return view('admin.learning-path.index', compact('recommendationEnabled', 'rules', 'courses', 'materials'));
    }

    public function create(Request $request)
    {
        $courses = Course::orderBy('code')->get();
        $materials = LearningMaterial::with('course:id,code,title')->orderBy('course_id')->orderBy('order_index')->get();
        $typeOptions = LearningPathRule::typeOptions();
        return view('admin.learning-path.create', compact('courses', 'materials', 'typeOptions'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'type' => ['required', 'in:course_prerequisite,material_prerequisite,difficulty_order'],
            'name' => ['nullable', 'string', 'max:255'],
            'source_course_id' => ['nullable', 'exists:courses,id'],
            'target_course_id' => ['nullable', 'exists:courses,id'],
            'source_material_id' => ['nullable', 'exists:learning_materials,id'],
            'target_material_id' => ['nullable', 'exists:learning_materials,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data = $request->only(['type', 'name', 'source_course_id', 'target_course_id', 'source_material_id', 'target_material_id', 'sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);
        $data['sort_order'] = $data['sort_order'] ?? LearningPathRule::max('sort_order') + 1;
        LearningPathRule::create($data);
        return redirect()->route('admin.learning-path.index')->with('success', 'Rule added.');
    }

    public function edit(LearningPathRule $learning_path_rule)
    {
        $rule = $learning_path_rule;
        $rule->load(['sourceCourse', 'targetCourse', 'sourceMaterial', 'targetMaterial']);
        $courses = Course::orderBy('code')->get();
        $materials = LearningMaterial::with('course:id,code,title')->orderBy('course_id')->orderBy('order_index')->get();
        $typeOptions = LearningPathRule::typeOptions();
        return view('admin.learning-path.edit', compact('rule', 'courses', 'materials', 'typeOptions'));
    }

    public function update(Request $request, LearningPathRule $learning_path_rule)
    {
        $request->validate([
            'type' => ['required', 'in:course_prerequisite,material_prerequisite,difficulty_order'],
            'name' => ['nullable', 'string', 'max:255'],
            'source_course_id' => ['nullable', 'exists:courses,id'],
            'target_course_id' => ['nullable', 'exists:courses,id'],
            'source_material_id' => ['nullable', 'exists:learning_materials,id'],
            'target_material_id' => ['nullable', 'exists:learning_materials,id'],
            'sort_order' => ['nullable', 'integer', 'min:0'],
            'is_active' => ['nullable', 'boolean'],
        ]);
        $data = $request->only(['type', 'name', 'source_course_id', 'target_course_id', 'source_material_id', 'target_material_id', 'sort_order']);
        $data['is_active'] = $request->boolean('is_active', true);
        $learning_path_rule->update($data);
        return redirect()->route('admin.learning-path.index')->with('success', 'Rule updated.');
    }

    public function destroy(LearningPathRule $learning_path_rule)
    {
        $learning_path_rule->delete();
        return redirect()->route('admin.learning-path.index')->with('success', 'Rule deleted.');
    }
}
