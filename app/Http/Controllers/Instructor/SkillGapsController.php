<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\LearningProgress;
use Illuminate\Http\Request;

class SkillGapsController extends Controller
{
    /**
     * Skill gaps and improvement report: weak areas and recommendations.
     */
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
            ->get();
        // Gaps: progress < 100%, grouped by student
        $byStudent = $progress->groupBy('student_id')->map(function ($items, $studentId) {
            $completed = $items->where('progress_percent', 100)->count();
            $total = $items->count();
            $weak = $items->where('progress_percent', '<', 100)->sortBy('progress_percent')->take(5);
            return [
                'student' => $items->first()->student,
                'completed' => $completed,
                'total' => $total,
                'completion_pct' => $total > 0 ? round(100 * $completed / $total, 1) : 0,
                'weak_areas' => $weak->values(),
            ];
        })->sortBy('completion_pct')->values();
        return view('instructor.skill-gaps.index', compact('byStudent'));
    }
}
