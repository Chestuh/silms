<?php

namespace App\Services;

use App\Models\Course;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\LearningMaterial;
use App\Models\LearningProgress;
use App\Models\Student;
use Illuminate\Support\Collection;

class AcademicCompletionStatusService
{
    /**
     * Get completion status summary for a student
     */
    public function getStudentCompletionSummary(Student $student): array
    {
        return [
            'courses' => $this->getCourseCompletionSummary($student),
            'activities' => $this->getActivityCompletionSummary($student),
            'payments' => $this->getPaymentCompletionSummary($student),
            'overall' => $this->getOverallCompletionStatus($student),
        ];
    }

    /**
     * Get course completion summary
     */
    public function getCourseCompletionSummary(Student $student): array
    {
        $enrollments = $student->enrollments()->with('course')->get();

        return [
            'total' => $enrollments->count(),
            'completed' => $enrollments->where('status', 'completed')->count(),
            'in_progress' => $enrollments->where('status', 'enrolled')->count(),
            'dropped' => $enrollments->where('status', 'dropped')->count(),
            'completion_percentage' => $enrollments->count() > 0 
                ? round(($enrollments->where('status', 'completed')->count() / $enrollments->count()) * 100) 
                : 0,
        ];
    }

    /**
     * Get learning activities completion summary
     */
    public function getActivityCompletionSummary(Student $student): array
    {
        $learningProgress = $student->learningProgress()->with('material')->get();

        $completed = $learningProgress->filter(fn($p) => $p->progress_percent === 100 && $p->completed_at !== null)->count();
        $inProgress = $learningProgress->filter(fn($p) => $p->progress_percent > 0 && $p->progress_percent < 100)->count();
        $pending = $learningProgress->filter(fn($p) => $p->progress_percent === 0)->count();

        return [
            'total' => $learningProgress->count(),
            'completed' => $completed,
            'in_progress' => $inProgress,
            'pending' => $pending,
            'completion_percentage' => $learningProgress->count() > 0
                ? round(($completed / $learningProgress->count()) * 100)
                : 0,
            'average_progress' => $learningProgress->count() > 0
                ? round($learningProgress->avg('progress_percent'))
                : 0,
        ];
    }

    /**
     * Get payment completion summary
     */
    public function getPaymentCompletionSummary(Student $student): array
    {
        $fees = $student->fees()->get();

        $paid = $fees->where('status', 'paid')->count();
        $pending = $fees->whereIn('status', ['pending', 'overdue'])->count();

        return [
            'total' => $fees->count(),
            'paid' => $paid,
            'pending' => $pending,
            'completion_percentage' => $fees->count() > 0
                ? round(($paid / $fees->count()) * 100)
                : 0,
            'total_amount' => $fees->sum('amount'),
            'paid_amount' => $fees->where('status', 'paid')->sum('amount'),
        ];
    }

    /**
     * Get overall completion status for a student
     */
    public function getOverallCompletionStatus(Student $student): array
    {
        $courses = $this->getCourseCompletionSummary($student);
        $activities = $this->getActivityCompletionSummary($student);
        $payments = $this->getPaymentCompletionSummary($student);

        $avgCompletion = round(
            ($courses['completion_percentage'] + $activities['completion_percentage'] + $payments['completion_percentage']) / 3
        );

        return [
            'overall_percentage' => $avgCompletion,
            'status' => $this->getStatusLabel($avgCompletion),
            'badge_class' => $this->getStatusBadgeClass($avgCompletion),
        ];
    }

    /**
     * Get all courses with completion status
     */
    public function getCoursesWithCompletionStatus(int $limit = 10): Collection
    {
        return Course::with(['enrollments', 'learningMaterials'])
            ->get()
            ->map(function (Course $course) {
                $enrollments = $course->enrollments->count();
                $completed = $course->enrollments->where('status', 'completed')->count();

                return [
                    'id' => $course->id,
                    'title' => $course->title,
                    'code' => $course->code,
                    'completion_status' => $course->completion_status,
                    'status_label' => $course->getCompletionStatusLabel(),
                    'badge_class' => $course->getCompletionBadgeClass(),
                    'students_enrolled' => $enrollments,
                    'students_completed' => $completed,
                    'student_completion_percentage' => $enrollments > 0 ? round(($completed / $enrollments) * 100) : 0,
                    'materials_count' => $course->learningMaterials->count(),
                    'materials_completed' => $course->learningMaterials->where('completion_status', 'completed')->count(),
                ];
            })
            ->take($limit);
    }

    /**
     * Get all activities with completion status
     */
    public function getActivitiesWithCompletionStatus(int $limit = 10): Collection
    {
        return LearningMaterial::with('learningProgress')
            ->get()
            ->map(function (LearningMaterial $material) {
                $progressRecords = $material->learningProgress;
                $completed = $progressRecords->filter(fn($p) => $p->progress_percent === 100)->count();

                return [
                    'id' => $material->id,
                    'title' => $material->title,
                    'format' => $material->format,
                    'completion_status' => $material->completion_status,
                    'status_label' => $material->getCompletionStatusLabel(),
                    'badge_class' => $material->getCompletionBadgeClass(),
                    'students_started' => $progressRecords->filter(fn($p) => $p->progress_percent > 0)->count(),
                    'students_completed' => $completed,
                    'average_progress' => $progressRecords->count() > 0 
                        ? round($progressRecords->avg('progress_percent'))
                        : 0,
                    'total_students' => $progressRecords->count(),
                ];
            })
            ->take($limit);
    }

    /**
     * Get completion status for a specific student and course
     */
    public function getStudentCourseCompletionStatus(Student $student, Course $course): array
    {
        $enrollment = $student->enrollments()->where('course_id', $course->id)->first();
        $materials = $course->learningMaterials()->get();

        $completedMaterials = 0;
        if ($materials->count() > 0) {
            $completedMaterials = $materials->filter(function ($material) use ($student) {
                $progress = $student->learningProgress()
                    ->where('material_id', $material->id)
                    ->first();
                return $progress && $progress->progress_percent === 100;
            })->count();
        }

        return [
            'enrollment_status' => $enrollment?->status ?? 'not_enrolled',
            'materials_total' => $materials->count(),
            'materials_completed' => $completedMaterials,
            'materials_completion_percentage' => $materials->count() > 0
                ? round(($completedMaterials / $materials->count()) * 100)
                : 0,
        ];
    }

    /**
     * Get status label based on completion percentage
     */
    private function getStatusLabel(int $percentage): string
    {
        return match (true) {
            $percentage === 100 => 'Completed',
            $percentage >= 50 => 'In Progress',
            $percentage > 0 => 'Started',
            default => 'Not Started',
        };
    }

    /**
     * Get badge class based on completion percentage
     */
    private function getStatusBadgeClass(int $percentage): string
    {
        return match (true) {
            $percentage === 100 => 'bg-success',
            $percentage >= 50 => 'bg-info',
            $percentage > 0 => 'bg-warning',
            default => 'bg-secondary',
        };
    }
}
