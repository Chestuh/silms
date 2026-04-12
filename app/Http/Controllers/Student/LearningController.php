<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;

class LearningController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $materials = LearningMaterial::with('course')
            ->where('archived', false)
            ->where(function ($q) {
                $q->whereNull('approval_status')->orWhere('approval_status', 'approved');
            })
            ->orderBy('order_index')
            ->orderBy('id')
            ->get()
            ->map(function ($m) use ($student) {
                $m->progress = $student->learningProgress()->where('material_id', $m->id)->first();
                return $m;
            });

        // Learning path guidance: recommend next materials (by course order, not yet completed)
        $recommendedNext = $materials->filter(function ($m) {
            $p = $m->progress;
            return !$p || $p->progress_percent < 100;
        })->take(3)->values();

        return view('student.learning', compact('materials', 'recommendedNext'));
    }

    public function show(Request $request, LearningMaterial $material)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);
        if ($material->archived) abort(404);

        $student = $request->user()->student;
        $progress = $student ? $student->learningProgress()->where('material_id', $material->id)->first() : null;

        $completed = $progress && $progress->progress_percent >= 100;

        $availableAids = $material->autoLearningAids()
            ->where('status', '!=', 'draft')
            ->whereNotNull('release_at')
            ->where('release_at', '<=', now())
            ->get();

        $lockedAids = $material->autoLearningAids()
            ->where(function ($q) use ($completed) {
                $q->whereNull('release_at')->orWhere('release_at', '>', now());
            })
            ->orWhere('status', 'draft')
            ->get();

        return view('student.material', compact('material', 'availableAids', 'lockedAids', 'completed'));
    }
}
