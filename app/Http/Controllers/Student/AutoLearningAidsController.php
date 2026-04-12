<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\AutoLearningAid;
use App\Models\LearningMaterial;
use Illuminate\Http\Request;

class AutoLearningAidsController extends Controller
{
    public function index(Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $completedMaterialIds = $student->learningProgress()
            ->where('progress_percent', '>=', 100)
            ->pluck('material_id');

        $aids = AutoLearningAid::with(['material.course', 'instructor'])
            ->whereIn('material_id', $completedMaterialIds)
            ->whereNotNull('release_at')
            ->where('release_at', '<=', now())
            ->where('status', '!=', 'draft')
            ->orderBy('release_at', 'desc')
            ->get();

        return view('student.learning-aids.index', compact('aids'));
    }

    public function download(AutoLearningAid $aid, Request $request)
    {
        $student = $request->user()->student;
        if (!$student) abort(403);

        $progress = $student->learningProgress()->where('material_id', $aid->material_id)->first();
        if (!$progress || $progress->progress_percent < 100) {
            abort(403, 'You must complete the related lesson first.');
        }

        if (!$aid->release_at || $aid->release_at->isFuture()) {
            abort(403, 'This resource is not released yet.');
        }

        return response()->download(storage_path('app/public/' . $aid->file_path));
    }
}
