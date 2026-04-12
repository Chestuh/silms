<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Course;
use App\Models\LearningMaterial;
use App\Models\Enrollment;
use App\Models\Fee;
use App\Models\Instructor;
use App\Models\CredentialRequest;
use App\Models\Grade;
use App\Models\LearningProgress;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        $kpis = [
            'users' => User::count(),
            'students' => Student::count(),
            'active_courses' => Course::count(),
            'courses' => Course::count(),
            'materials_uploaded' => LearningMaterial::count(),
            'materials' => LearningMaterial::count(),
            'active_enrollments' => Enrollment::where('status', 'enrolled')->count(),
            'pending_fees_count' => Fee::where('status', 'pending')->count(),
            'pending_fees_amount' => Fee::where('status', 'pending')->sum('amount'),
            'credentials_pending' => CredentialRequest::whereIn('status', ['pending', 'processing'])->count(),
            'avg_grade' => round(Grade::avg('final_grade') ?? 0, 1),
            'pending_approvals' => 
                \App\Models\PreRegistration::where('status', 'pending')->count() +
                CredentialRequest::whereIn('status', ['pending', 'processing'])->count(),
            'active_students' => Student::where('status', 'active')->count(),
        ];

        // Calculate pass rate
        $totalGradedEnrollments = Grade::whereNotNull('final_grade')->count();
        $passedEnrollments = Grade::where('final_grade', '>=', 75)->count();
        $kpis['pass_rate'] = $totalGradedEnrollments > 0 ? round(($passedEnrollments / $totalGradedEnrollments) * 100, 1) : 0;

        // Calculate completion rate
        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $kpis['completion_rate'] = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;

        // Calculate engagement (average time spent on materials in minutes)
        $kpis['avg_engagement_time'] = LearningProgress::avg('time_spent_minutes') ?? 0;

        // Load recent pending pre-registrations for quick actions
        $recentApplications = \App\Models\PreRegistration::where('status', 'pending')
            ->orderBy('created_at', 'desc')
            ->limit(10)
            ->get();

        return view('admin.dashboard', compact('kpis', 'recentApplications'));
    }

    public function liveEnrollmentData(Request $request)
    {
        $year = now()->year;
        $monthlyCounts = Enrollment::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupBy('month')
            ->orderBy('month')
            ->pluck('total', 'month')
            ->toArray();

        $labels = [];
        $counts = [];

        for ($month = 1; $month <= 12; $month++) {
            $labels[] = Carbon::createFromDate($year, $month, 1)->format('M');
            $counts[] = isset($monthlyCounts[$month]) ? (int) $monthlyCounts[$month] : 0;
        }

        return response()->json([
            'year' => $year,
            'labels' => $labels,
            'data' => $counts,
            'lastUpdated' => now()->toISOString(),
        ]);
    }

    public function liveMaterialsUsageData(Request $request)
    {
        $usage = LearningProgress::selectRaw('courses.title as subject, COUNT(learning_progress.id) as interactions')
            ->join('learning_materials', 'learning_progress.material_id', '=', 'learning_materials.id')
            ->join('courses', 'learning_materials.course_id', '=', 'courses.id')
            ->groupBy('courses.title')
            ->orderByDesc('interactions')
            ->limit(5)
            ->pluck('interactions', 'subject')
            ->toArray();

        if (empty($usage)) {
            $usage = LearningMaterial::selectRaw('courses.title as subject, COUNT(learning_materials.id) as interactions')
                ->join('courses', 'learning_materials.course_id', '=', 'courses.id')
                ->groupBy('courses.title')
                ->orderByDesc('interactions')
                ->limit(5)
                ->pluck('interactions', 'subject')
                ->toArray();
        }

        return response()->json([
            'labels' => array_keys($usage),
            'data' => array_values($usage),
            'topSubject' => array_key_first($usage) ?: 'N/A',
            'lastUpdated' => now()->toISOString(),
        ]);
    }

    public function liveGradeDistributionData(Request $request)
    {
        $segments = [
            '90-100' => Grade::whereBetween('final_grade', [90, 100])->count(),
            '80-89' => Grade::whereBetween('final_grade', [80, 89])->count(),
            '70-79' => Grade::whereBetween('final_grade', [70, 79])->count(),
            '< 70' => Grade::where('final_grade', '<', 70)->count(),
        ];

        return response()->json([
            'labels' => array_keys($segments),
            'data' => array_values($segments),
            'passRate' => $this->calculatePassRate(),
            'lastUpdated' => now()->toISOString(),
        ]);
    }

    public function liveKpiMetrics(Request $request)
    {
        $totalGradedEnrollments = Grade::whereNotNull('final_grade')->count();
        $passedEnrollments = Grade::where('final_grade', '>=', 75)->count();
        $passRate = $totalGradedEnrollments > 0 ? round(($passedEnrollments / $totalGradedEnrollments) * 100, 1) : 0;

        $totalEnrollments = Enrollment::count();
        $completedEnrollments = Enrollment::where('status', 'completed')->count();
        $completionRate = $totalEnrollments > 0 ? round(($completedEnrollments / $totalEnrollments) * 100, 1) : 0;

        $avgEngagement = LearningProgress::avg('time_spent_minutes') ?? 0;

        return response()->json([
            'passRate' => $passRate,
            'completionRate' => $completionRate,
            'avgEngagement' => round($avgEngagement),
            'lastUpdated' => now()->toISOString(),
        ]);
    }

    protected function calculatePassRate(): float
    {
        $totalGradedEnrollments = Grade::whereNotNull('final_grade')->count();
        $passedEnrollments = Grade::where('final_grade', '>=', 75)->count();

        return $totalGradedEnrollments > 0 ? round(($passedEnrollments / $totalGradedEnrollments) * 100, 1) : 0;
    }
}
