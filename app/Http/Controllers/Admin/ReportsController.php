<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Student;
use App\Models\Instructor;
use App\Models\Course;
use App\Models\Enrollment;
use App\Models\LearningMaterial;
use App\Models\LearningProgress;
use App\Models\Fee;
use App\Models\CredentialRequest;
use App\Models\PreRegistration;
use App\Models\DisciplinaryRecord;
use App\Models\Grade;
use App\Models\MaterialRating;
use App\Models\Message;
use App\Models\AcademicHonor;
use App\Models\AdmissionRecord;
use Illuminate\Http\Request;

class ReportsController extends Controller
{
    public function index(Request $request)
    {
        // Get date range from request or use default (past 30 days)
        $startDate = $request->input('start_date') 
            ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay()
            : \Carbon\Carbon::now()->subDays(30);
        
        $endDate = $request->input('end_date')
            ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();

        // Original Stats
        $stats = [
            'users_total' => User::count(),
            'students_total' => Student::count(),
            'students_active' => Student::where('status', 'active')->count(),
            'instructors_total' => Instructor::count(),
            'courses_total' => Course::count(),
            'enrollments_active' => Enrollment::where('status', 'enrolled')->count(),
            'materials_total' => LearningMaterial::count(),
            'materials_archived' => LearningMaterial::where('archived', true)->count(),
            'progress_records' => LearningProgress::count(),
            'progress_completed' => LearningProgress::where('progress_percent', 100)->count(),
            'fees_pending_count' => Fee::where('status', 'pending')->count(),
            'fees_pending_amount' => Fee::where('status', 'pending')->sum('amount'),
            'credentials_pending' => CredentialRequest::whereIn('status', ['pending', 'processing'])->count(),
            'credentials_ready' => CredentialRequest::where('status', 'ready')->count(),
        ];

        $stats['completion_rate'] = $stats['progress_records'] > 0
            ? round(100 * $stats['progress_completed'] / $stats['progress_records'], 1)
            : 0;

        // Pre-Registration Stats
        $stats['pre_registrations_pending'] = PreRegistration::where('status', 'pending')->count();
        $stats['pre_registrations_approved'] = PreRegistration::where('status', 'approved')->count();
        $stats['pre_registrations_rejected'] = PreRegistration::where('status', 'rejected')->count();

        // Disciplinary Stats
        $stats['disciplinary_records'] = DisciplinaryRecord::count();
        $stats['disciplinary_recent'] = DisciplinaryRecord::whereBetween('created_at', [$startDate, $endDate])->count();

        // Academic Performance Stats
        $stats['grades_total'] = Grade::count();
        $stats['avg_final_grade'] = round(Grade::avg('final_grade') ?? 0, 2);
        $stats['honors_count'] = AcademicHonor::count();

        // Material Ratings Stats
        $stats['material_ratings_count'] = MaterialRating::count();
        $stats['avg_material_rating'] = round(MaterialRating::avg('rating') ?? 0, 2);

        // Messaging Stats
        $stats['messages_total'] = Message::count();
        $stats['messages_recent'] = Message::whereBetween('created_at', [$startDate, $endDate])->count();

        // Admission Stats
        $stats['admission_records'] = AdmissionRecord::count();

        // Fee Collection Stats (by status)
        $stats['fees_paid_count'] = Fee::where('status', 'paid')->count();
        $stats['fees_paid_amount'] = Fee::where('status', 'paid')->sum('amount');

        // Enrollment by course top 5
        $stats['enrollments_by_course'] = Course::withCount('enrollments')
            ->orderByDesc('enrollments_count')
            ->limit(5)
            ->get()
            ->map(function($course) {
                return [
                    'name' => $course->name,
                    'count' => $course->enrollments_count
                ];
            });

        // Student status breakdown
        $stats['students_by_status'] = Student::selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->get()
            ->pluck('count', 'status');

        // Fee status breakdown
        $stats['fees_by_status'] = Fee::selectRaw('status, count(*) as count, sum(amount) as total_amount')
            ->groupBy('status')
            ->get()
            ->map(function($fee) {
                return [
                    'status' => $fee->status,
                    'count' => $fee->count,
                    'amount' => $fee->total_amount
                ];
            });

        // Learning material status breakdown by format
        $stats['materials_by_type'] = LearningMaterial::selectRaw('format, count(*) as count')
            ->groupBy('format')
            ->get()
            ->pluck('count', 'format');

        return view('admin.reports.index', compact('stats', 'startDate', 'endDate'));
    }

    public function export(Request $request)
    {
        $format = $request->input('format', 'csv');

        // Get date range
        $startDate = $request->input('start_date') 
            ? \Carbon\Carbon::parse($request->input('start_date'))->startOfDay()
            : \Carbon\Carbon::now()->subDays(30);
        
        $endDate = $request->input('end_date')
            ? \Carbon\Carbon::parse($request->input('end_date'))->endOfDay()
            : \Carbon\Carbon::now()->endOfDay();

        // Gather all stats (similar to index method)
        $stats = [
            'users_total' => User::count(),
            'students_total' => Student::count(),
            'students_active' => Student::where('status', 'active')->count(),
            'instructors_total' => Instructor::count(),
            'courses_total' => Course::count(),
            'enrollments_active' => Enrollment::where('status', 'enrolled')->count(),
            'materials_total' => LearningMaterial::count(),
            'materials_archived' => LearningMaterial::where('archived', true)->count(),
            'progress_records' => LearningProgress::count(),
            'progress_completed' => LearningProgress::where('progress_percent', 100)->count(),
            'fees_pending_count' => Fee::where('status', 'pending')->count(),
            'fees_pending_amount' => Fee::where('status', 'pending')->sum('amount'),
            'credentials_pending' => CredentialRequest::whereIn('status', ['pending', 'processing'])->count(),
            'credentials_ready' => CredentialRequest::where('status', 'ready')->count(),
            'pre_registrations_pending' => PreRegistration::where('status', 'pending')->count(),
            'pre_registrations_approved' => PreRegistration::where('status', 'approved')->count(),
            'pre_registrations_rejected' => PreRegistration::where('status', 'rejected')->count(),
            'disciplinary_records' => DisciplinaryRecord::count(),
            'grades_total' => Grade::count(),
            'avg_final_grade' => round(Grade::avg('final_grade') ?? 0, 2),
            'honors_count' => AcademicHonor::count(),
            'material_ratings_count' => MaterialRating::count(),
            'avg_material_rating' => round(MaterialRating::avg('rating') ?? 0, 2),
            'messages_total' => Message::count(),
            'admission_records' => AdmissionRecord::count(),
            'fees_paid_count' => Fee::where('status', 'paid')->count(),
            'fees_paid_amount' => Fee::where('status', 'paid')->sum('amount'),
        ];

        $stats['completion_rate'] = $stats['progress_records'] > 0
            ? round(100 * $stats['progress_completed'] / $stats['progress_records'], 1)
            : 0;

        if ($format === 'csv') {
            return $this->exportCsv($stats, $startDate, $endDate);
        }

        return redirect()->back()->with('error', 'Invalid export format');
    }

    private function exportCsv($stats, $startDate, $endDate)
    {
        $filename = 'school-reports-' . now()->format('Y-m-d-His') . '.csv';
        
        $callback = function() use ($stats, $startDate, $endDate) {
            $data = fopen('php://output', 'w');
            
            // Add BOM for proper UTF-8 encoding in Excel
            fprintf($data, chr(0xEF).chr(0xBB).chr(0xBF));

            // Write headers
            fputcsv($data, ['School Reports and Analytics']);
            fputcsv($data, ['Report Generated', now()->format('Y-m-d H:i:s')]);
            fputcsv($data, ['Date Range', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d')]);
            fputcsv($data, []);

            // User Management Section
            fputcsv($data, ['USER MANAGEMENT']);
            fputcsv($data, ['Metric', 'Count']);
            fputcsv($data, ['Total Users', $stats['users_total']]);
            fputcsv($data, ['Total Students', $stats['students_total']]);
            fputcsv($data, ['Active Students', $stats['students_active']]);
            fputcsv($data, ['Total Instructors', $stats['instructors_total']]);
            fputcsv($data, []);

            // Academic Section
            fputcsv($data, ['ACADEMIC METRICS']);
            fputcsv($data, ['Metric', 'Value']);
            fputcsv($data, ['Total Courses', $stats['courses_total']]);
            fputcsv($data, ['Active Enrollments', $stats['enrollments_active']]);
            fputcsv($data, ['Average Final Grade', $stats['avg_final_grade']]);
            fputcsv($data, ['Total Grades', $stats['grades_total']]);
            fputcsv($data, ['Academic Honors', $stats['honors_count']]);
            fputcsv($data, []);

            // Learning Section
            fputcsv($data, ['LEARNING & MATERIALS']);
            fputcsv($data, ['Metric', 'Value']);
            fputcsv($data, ['Learning Materials', $stats['materials_total']]);
            fputcsv($data, ['Archived Materials', $stats['materials_archived']]);
            fputcsv($data, ['Progress Records', $stats['progress_records']]);
            fputcsv($data, ['Completed Progress', $stats['progress_completed']]);
            fputcsv($data, ['Completion Rate (%)', $stats['completion_rate']]);
            fputcsv($data, ['Average Material Rating', $stats['avg_material_rating']]);
            fputcsv($data, ['Material Ratings Count', $stats['material_ratings_count']]);
            fputcsv($data, []);

        // Materials by Format
        fputcsv($data, ['MATERIALS BY FORMAT']);
        fputcsv($data, ['Format', 'Count']);
        foreach ($stats['materials_by_type'] as $format => $count) {
            fputcsv($data, [$format, $count]);
        }
        fputcsv($data, []);
            fputcsv($data, ['PRE-REGISTRATIONS']);
            fputcsv($data, ['Status', 'Count']);
            fputcsv($data, ['Pending', $stats['pre_registrations_pending']]);
            fputcsv($data, ['Approved', $stats['pre_registrations_approved']]);
            fputcsv($data, ['Rejected', $stats['pre_registrations_rejected']]);
            fputcsv($data, []);

            // Administrative Section
            fputcsv($data, ['ADMINISTRATIVE']);
            fputcsv($data, ['Metric', 'Value']);
            fputcsv($data, ['Disciplinary Records', $stats['disciplinary_records']]);
            fputcsv($data, ['Admission Records', $stats['admission_records']]);
            fputcsv($data, ['Credentials Pending', $stats['credentials_pending']]);
            fputcsv($data, ['Credentials Ready', $stats['credentials_ready']]);
            fputcsv($data, ['Messages Total', $stats['messages_total']]);
            fputcsv($data, []);

            fclose($data);
        };

        $headers = [
            'Content-Type' => 'text/csv; charset=utf-8',
            'Content-Disposition' => "attachment; filename=\"{$filename}\"",
            'Pragma' => 'no-cache',
            'Expires' => '0',
        ];

        return response()->stream($callback, 200, $headers);
    }
}
