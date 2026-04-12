<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\LearningProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $instructor = $request->user()->instructor;
        if (!$instructor) {
            abort(403);
        }
        $courseIds = $instructor->courses()->pluck('id');
        $progress = LearningProgress::whereHas('material', function ($q) use ($courseIds) {
            $q->whereIn('course_id', $courseIds);
        })
            ->with(['material.course', 'student.user'])
            ->orderByDesc('updated_at')
            ->paginate(20);
        $courses = $instructor->courses()->orderBy('code')->get();
        return view('instructor.progress.index', compact('progress', 'courses'));
    }
}
