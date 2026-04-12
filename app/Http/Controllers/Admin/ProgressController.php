<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Models\LearningProgress;
use Illuminate\Http\Request;

class ProgressController extends Controller
{
    public function index(Request $request)
    {
        $students = Student::with('user')
            ->withCount(['learningProgress as completed_count' => function ($q) {
                $q->where('progress_percent', 100);
            }])
            ->withCount('learningProgress as total_progress_count')
            ->orderBy('student_number')
            ->paginate(20);
        $globalStats = [
            'total_progress_records' => LearningProgress::count(),
            'total_completed' => LearningProgress::where('progress_percent', 100)->count(),
        ];
        return view('admin.progress.index', compact('students', 'globalStats'));
    }
}
