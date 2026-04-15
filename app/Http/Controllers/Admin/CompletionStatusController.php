<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Student;
use App\Services\AcademicCompletionStatusService;

class CompletionStatusController extends Controller
{
    protected AcademicCompletionStatusService $completionService;

    public function __construct(AcademicCompletionStatusService $completionService)
    {
        $this->completionService = $completionService;
    }

    /**
     * Display academic completion status overview
     */
    public function index()
    {
        $courses = $this->completionService->getCoursesWithCompletionStatus();
        $activities = $this->completionService->getActivitiesWithCompletionStatus();

        return view('admin.completion-status.index', [
            'courses' => $courses,
            'activities' => $activities,
        ]);
    }

    /**
     * Display completion status for a specific student
     */
    public function student(Student $student)
    {
        $completionSummary = $this->completionService->getStudentCompletionSummary($student);

        return view('admin.completion-status.index', [
            'student' => $student,
            'completionSummary' => $completionSummary,
        ]);
    }

    /**
     * Get JSON response for API/AJAX requests
     */
    public function apiStatus(Student $student)
    {
        $completionSummary = $this->completionService->getStudentCompletionSummary($student);

        return response()->json($completionSummary);
    }
}
