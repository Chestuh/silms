<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningPathRule;
use Illuminate\Http\Request;

class LearningPathController extends Controller
{
    /**
     * Learning path guidance: suggested learning sequence based on performance, goals, and admin rules.
     */
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) {
            abort(403);
        }
        $enrollments = $student->enrollments()
            ->where('status', 'enrolled')
            ->with('course.learningMaterials')
            ->get()
            ->filter(fn ($e) => $e->course);
        $completedMaterialIds = $student->learningProgress()->where('progress_percent', 100)->pluck('material_id')->toArray();
        $rules = LearningPathRule::where('is_active', true)->orderBy('sort_order')->get();

        $recommended = [];
        foreach ($enrollments as $e) {
            $materials = $e->course->learningMaterials
                ->where('archived', false)
                ->filter(fn ($m) => $m->approval_status === 'approved' || $m->approval_status === null)
                ->sortBy('order_index');
            foreach ($materials as $m) {
                $recommended[] = [
                    'material' => $m,
                    'course' => $e->course,
                    'completed' => in_array($m->id, $completedMaterialIds),
                    'progress' => $student->learningProgress()->where('material_id', $m->id)->first(),
                    'locked' => false,
                    'lock_reason' => null,
                ];
            }
        }

        // Apply course prerequisites: lock target course materials until source course is completed
        $courseMaterialIds = [];
        foreach ($enrollments as $e) {
            $courseMaterialIds[$e->course_id] = $e->course->learningMaterials
                ->where('archived', false)
                ->filter(fn ($m) => $m->approval_status === 'approved' || $m->approval_status === null)
                ->pluck('id')
                ->toArray();
        }
        foreach ($rules->where('type', 'course_prerequisite') as $rule) {
            if (!$rule->source_course_id || !$rule->target_course_id) {
                continue;
            }
            $sourceIds = $courseMaterialIds[$rule->source_course_id] ?? [];
            $targetIds = $courseMaterialIds[$rule->target_course_id] ?? [];
            $sourceComplete = $sourceIds !== [] && !array_diff($sourceIds, $completedMaterialIds);
            if (!$sourceComplete) {
                foreach ($recommended as &$item) {
                    if (in_array($item['material']->id, $targetIds)) {
                        $item['locked'] = true;
                        $item['lock_reason'] = 'Complete ' . ($rule->sourceCourse->code ?? 'prerequisite course') . ' first.';
                    }
                }
            }
        }

        // Apply material prerequisites: lock target material until source material is completed
        foreach ($rules->where('type', 'material_prerequisite') as $rule) {
            if (!$rule->source_material_id || !$rule->target_material_id) {
                continue;
            }
            if (!in_array($rule->source_material_id, $completedMaterialIds)) {
                foreach ($recommended as &$item) {
                    if ($item['material']->id == $rule->target_material_id) {
                        $item['locked'] = true;
                        $item['lock_reason'] = 'Complete "' . ($rule->sourceMaterial->title ?? 'prerequisite') . '" first.';
                    }
                }
            }
        }

        // Sort: unlocked first, then by difficulty (easy→medium→hard) if rule active
        $difficultyOrder = $rules->where('type', 'difficulty_order')->isNotEmpty();
        $difficultyRank = ['easy' => 0, 'medium' => 1, 'hard' => 2];
        usort($recommended, function ($a, $b) use ($difficultyOrder, $difficultyRank) {
            if ($a['locked'] !== $b['locked']) {
                return $a['locked'] ? 1 : -1;
            }
            if ($difficultyOrder) {
                $da = $difficultyRank[$a['material']->difficulty_level ?? 'medium'] ?? 1;
                $db = $difficultyRank[$b['material']->difficulty_level ?? 'medium'] ?? 1;
                return $da <=> $db;
            }
            return 0;
        });

        return view('student.learning-path', compact('recommended'));
    }
}
