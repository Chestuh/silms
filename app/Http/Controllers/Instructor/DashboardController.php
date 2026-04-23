<?php

namespace App\Http\Controllers\Instructor;

use App\Http\Controllers\Controller;
use App\Models\Enrollment;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $user = $request->user();
        $instructor = $user->instructor;
        if (! $instructor) {
            abort(403);
        }

        $courseIds = $instructor->courses()->pluck('id');
        $coursesCount = $instructor->courses()->count();
        $materialsCount = \App\Models\LearningMaterial::whereIn('course_id', $courseIds)->count();
        $enrollmentsCount = Enrollment::whereIn('course_id', $courseIds)->where('status', 'enrolled')->count();
        $studentsCount = Enrollment::whereIn('course_id', $courseIds)->where('status', 'enrolled')->distinct()->count('student_id');
        $averageStudentsPerCourse = $coursesCount > 0 ? round($enrollmentsCount / $coursesCount, 1) : 0;
        $unreadMessages = $user->receivedMessages()->whereNull('read_at')->count();
        $progressCompleted = \App\Models\LearningProgress::whereHas('material', fn ($q) => $q->whereIn('course_id', $courseIds))->where('progress_percent', 100)->count();
        $progressTotal = \App\Models\LearningProgress::whereHas('material', fn ($q) => $q->whereIn('course_id', $courseIds))->count();
        $completionRate = $progressTotal > 0 ? round(100 * $progressCompleted / $progressTotal, 1) : 0;

        $kpis = [
            'courses' => $coursesCount,
            'learning_materials' => $materialsCount,
            'enrolled_students' => $enrollmentsCount,
            'distinct_students' => $studentsCount,
            'average_students_per_course' => $averageStudentsPerCourse,
            'unread_messages' => $unreadMessages,
            'completion_rate' => $completionRate,
            'progress_completed' => $progressCompleted,
            'progress_total' => $progressTotal,
        ];

        return view('instructor.dashboard', compact('instructor', 'kpis'));
    }
}
