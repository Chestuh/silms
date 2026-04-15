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
use PhpOffice\PhpSpreadsheet\Spreadsheet;
use PhpOffice\PhpSpreadsheet\Writer\Xlsx;
use PhpOffice\PhpSpreadsheet\Chart\Chart;
use PhpOffice\PhpSpreadsheet\Chart\DataSeries;
use PhpOffice\PhpSpreadsheet\Chart\DataSeriesValues;
use PhpOffice\PhpSpreadsheet\Chart\Layout;
use PhpOffice\PhpSpreadsheet\Chart\Legend;
use PhpOffice\PhpSpreadsheet\Chart\PlotArea;
use PhpOffice\PhpSpreadsheet\Chart\Title;

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
            'fees_by_status' => Fee::selectRaw('status, count(*) as count, sum(amount) as total_amount')
                ->groupBy('status')
                ->get()
                ->map(function ($fee) {
                    return [
                        'status' => $fee->status,
                        'count' => $fee->count,
                        'amount' => $fee->total_amount,
                    ];
                })
                ->toArray(),
            'students_by_status' => Student::selectRaw('status, count(*) as count')
                ->groupBy('status')
                ->get()
                ->pluck('count', 'status')
                ->toArray(),
            'enrollments_by_course' => Course::withCount('enrollments')
                ->orderByDesc('enrollments_count')
                ->limit(10)
                ->get()
                ->map(function ($course) {
                    return [
                        'name' => $course->name,
                        'count' => $course->enrollments_count,
                    ];
                })
                ->toArray(),
            'materials_by_type' => LearningMaterial::selectRaw('format, count(*) as count')
                ->groupBy('format')
                ->get()
                ->pluck('count', 'format')
                ->toArray(),
        ];

        $stats['completion_rate'] = $stats['progress_records'] > 0
            ? round(100 * $stats['progress_completed'] / $stats['progress_records'], 1)
            : 0;

        if ($format === 'csv') {
            return $this->exportCsv($stats, $startDate, $endDate);
        }

        if ($format === 'xlsx') {
            return $this->exportXlsx($stats, $startDate, $endDate);
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

    private function exportXlsx($stats, $startDate, $endDate)
    {
        $spreadsheet = new Spreadsheet();
        $sheet = $spreadsheet->getActiveSheet();
        $sheet->setTitle('School Reports');

        $sheet->setCellValue('A1', 'School Reports and Analytics');
        $sheet->setCellValue('A2', 'Report Generated');
        $sheet->setCellValue('B2', now()->format('Y-m-d H:i:s'));
        $sheet->setCellValue('A3', 'Date Range');
        $sheet->setCellValue('B3', $startDate->format('Y-m-d') . ' to ' . $endDate->format('Y-m-d'));

        $sheet->getColumnDimension('A')->setWidth(28);
        $sheet->getColumnDimension('B')->setWidth(18);
        $sheet->getColumnDimension('D')->setWidth(16);
        $sheet->getColumnDimension('E')->setWidth(14);
        $sheet->getColumnDimension('G')->setWidth(20);
        $sheet->getColumnDimension('H')->setWidth(14);
        $sheet->getColumnDimension('J')->setWidth(20);
        $sheet->getColumnDimension('K')->setWidth(14);

        $row = 5;
        $sheet->setCellValue('A' . $row, 'USER MANAGEMENT');
        $row++;
        $sheet->setCellValue('A' . $row, 'Metric');
        $sheet->setCellValue('B' . $row, 'Value');
        $row++;
        $sheet->setCellValue('A' . $row, 'Total Users');
        $sheet->setCellValue('B' . $row++, $stats['users_total']);
        $sheet->setCellValue('A' . $row, 'Total Students');
        $sheet->setCellValue('B' . $row++, $stats['students_total']);
        $sheet->setCellValue('A' . $row, 'Active Students');
        $sheet->setCellValue('B' . $row++, $stats['students_active']);
        $sheet->setCellValue('A' . $row, 'Total Instructors');
        $sheet->setCellValue('B' . $row++, $stats['instructors_total']);

        $sectionRow = 5;
        $sheet->setCellValue('D' . $sectionRow, 'FEE STATUS');
        $sheet->setCellValue('D' . ($sectionRow + 1), 'Status');
        $sheet->setCellValue('E' . ($sectionRow + 1), 'Amount');
        $feeRow = $sectionRow + 2;
        if (empty($stats['fees_by_status'])) {
            $stats['fees_by_status'] = [['status' => 'none', 'count' => 0, 'amount' => 0]];
        }
        foreach ($stats['fees_by_status'] as $statusData) {
            $sheet->setCellValue('D' . $feeRow, ucfirst($statusData['status']));
            $sheet->setCellValue('E' . $feeRow, $statusData['amount']);
            $feeRow++;
        }

        $courseRow = 5;
        $sheet->setCellValue('G' . $courseRow, 'TOP COURSES BY ENROLLMENT');
        $sheet->setCellValue('G' . ($courseRow + 1), 'Course');
        $sheet->setCellValue('H' . ($courseRow + 1), 'Enrollments');
        $courseRow += 2;
        if (empty($stats['enrollments_by_course'])) {
            $stats['enrollments_by_course'] = [['name' => 'None', 'count' => 0]];
        }
        foreach ($stats['enrollments_by_course'] as $course) {
            $sheet->setCellValue('G' . $courseRow, $course['name']);
            $sheet->setCellValue('H' . $courseRow, $course['count']);
            $courseRow++;
        }

        $studentStatusRow = 5;
        $sheet->setCellValue('J' . $studentStatusRow, 'STUDENT STATUS');
        $sheet->setCellValue('J' . ($studentStatusRow + 1), 'Status');
        $sheet->setCellValue('K' . ($studentStatusRow + 1), 'Count');
        $studentStatusRow += 2;
        if (empty($stats['students_by_status'])) {
            $stats['students_by_status'] = ['none' => 0];
        }
        foreach ($stats['students_by_status'] as $status => $count) {
            $sheet->setCellValue('J' . $studentStatusRow, ucfirst($status));
            $sheet->setCellValue('K' . $studentStatusRow, $count);
            $studentStatusRow++;
        }

        $completionRow = max($row, $feeRow, $courseRow, $studentStatusRow) + 2;
        $sheet->setCellValue('A' . $completionRow, 'COMPLETION RATE');
        $sheet->setCellValue('A' . ($completionRow + 1), 'Complete');
        $sheet->setCellValue('B' . ($completionRow + 1), $stats['completion_rate']);
        $sheet->setCellValue('A' . ($completionRow + 2), 'Remaining');
        $sheet->setCellValue('B' . ($completionRow + 2), 100 - $stats['completion_rate']);

        $feeStatusCount = max(count($stats['fees_by_status']), 1);
        $feeStatusLabels = new DataSeriesValues('String', "'{$sheet->getTitle()}'!\$D\$7:\$D\$" . ($feeRow - 1), null, $feeStatusCount);
        $feeStatusValues = new DataSeriesValues('Number', "'{$sheet->getTitle()}'!\$E\$7:\$E\$" . ($feeRow - 1), null, $feeStatusCount);
        $feeSeries = new DataSeries(DataSeries::TYPE_PIECHART, null, range(0, $feeStatusCount - 1), [], [$feeStatusLabels], [$feeStatusValues]);
        $feePlotArea = new PlotArea(new Layout(), [$feeSeries]);
        $feeChart = new Chart('fee_status', new Title('Fee Status Breakdown'), new Legend(Legend::POSITION_RIGHT, null, false), $feePlotArea, true, 0, null, null);
        $feeChart->setTopLeftPosition('D12');
        $feeChart->setBottomRightPosition('K24');
        $sheet->addChart($feeChart);

        $courseCount = max(count($stats['enrollments_by_course']), 1);
        $courseLabels = new DataSeriesValues('String', "'{$sheet->getTitle()}'!\$G\$7:\$G\$" . ($courseRow - 1), null, $courseCount);
        $courseValues = new DataSeriesValues('Number', "'{$sheet->getTitle()}'!\$H\$7:\$H\$" . ($courseRow - 1), null, $courseCount);
        $courseSeries = new DataSeries(DataSeries::TYPE_BARCHART, DataSeries::GROUPING_CLUSTERED, range(0, $courseCount - 1), [], [$courseLabels], [$courseValues]);
        $courseSeries->setPlotDirection(DataSeries::DIRECTION_COL);
        $coursePlotArea = new PlotArea(new Layout(), [$courseSeries]);
        $courseChart = new Chart('course_enrollment', new Title('Top Courses by Enrollment'), new Legend(Legend::POSITION_RIGHT, null, false), $coursePlotArea, true, 0, null, null);
        $courseChart->setTopLeftPosition('G26');
        $courseChart->setBottomRightPosition('N40');
        $sheet->addChart($courseChart);

        $statusCount = max(count($stats['students_by_status']), 1);
        $statusLabels = new DataSeriesValues('String', "'{$sheet->getTitle()}'!\$J\$7:\$J\$" . ($studentStatusRow - 1), null, $statusCount);
        $statusValues = new DataSeriesValues('Number', "'{$sheet->getTitle()}'!\$K\$7:\$K\$" . ($studentStatusRow - 1), null, $statusCount);
        $statusSeries = new DataSeries(DataSeries::TYPE_DOUGHNUTCHART, null, range(0, $statusCount - 1), [], [$statusLabels], [$statusValues]);
        $statusPlotArea = new PlotArea(new Layout(), [$statusSeries]);
        $statusChart = new Chart('student_status', new Title('Student Status Distribution'), new Legend(Legend::POSITION_RIGHT, null, false), $statusPlotArea, true, 0, null, null);
        $statusChart->setTopLeftPosition('A' . ($completionRow + 4));
        $statusChart->setBottomRightPosition('F' . ($completionRow + 18));
        $sheet->addChart($statusChart);

        $completionLabels = new DataSeriesValues('String', "'{$sheet->getTitle()}'!\$A\$" . ($completionRow + 1) . ":\$A\$" . ($completionRow + 2), null, 2);
        $completionValues = new DataSeriesValues('Number', "'{$sheet->getTitle()}'!\$B\$" . ($completionRow + 1) . ":\$B\$" . ($completionRow + 2), null, 2);
        $completionSeries = new DataSeries(DataSeries::TYPE_PIECHART, null, [0, 1], [], [$completionLabels], [$completionValues]);
        $completionPlotArea = new PlotArea(new Layout(), [$completionSeries]);
        $completionChart = new Chart('completion_rate', new Title('Completion Rate'), new Legend(Legend::POSITION_RIGHT, null, false), $completionPlotArea, true, 0, null, null);
        $completionChart->setTopLeftPosition('G' . ($completionRow + 4));
        $completionChart->setBottomRightPosition('N' . ($completionRow + 18));
        $sheet->addChart($completionChart);

        $writer = new Xlsx($spreadsheet);
        $filename = 'school-reports-' . now()->format('Y-m-d-His') . '.xlsx';

        return response()->streamDownload(function () use ($writer) {
            $writer->setIncludeCharts(true);
            $writer->save('php://output');
        }, $filename, [
            'Content-Type' => 'application/vnd.openxmlformats-officedocument.spreadsheetml.sheet',
            'Cache-Control' => 'max-age=0',
        ]);
    }
}
