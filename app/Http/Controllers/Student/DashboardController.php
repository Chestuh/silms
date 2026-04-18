<?php

namespace App\Http\Controllers\Student;

use App\Http\Controllers\Controller;
use App\Models\Setting;
use App\Models\Student;
use Illuminate\Http\Request;

class DashboardController extends Controller
{
    private const KPI_WIDGETS = [
        'enrolled_courses' => 'Enrolled Courses',
        'materials_completed' => 'Materials Completed',
        'materials_started' => 'Materials Started',
        'average_grade' => 'Average Grade',
        'gwa' => 'GWA',
        'pending_fees' => 'Pending Fees',
        'study_time_minutes' => 'Study Time (min)',
        'credentials_pending' => 'Credentials Pending',
        'unread_messages' => 'Unread Messages',
    ];

    public function index(Request $request)
    {
        $user = $request->user();
        $student = $user->student;
        if (! $student) {
            // Create a Student record if it doesn't exist for a user with student role
            $student = Student::create([
                'user_id' => $user->id,
                'student_number' => 'STU-' . $user->id . '-' . date('Y'),
                'program' => 'General',
                'year_level' => 1,
                'admission_date' => now(),
                'status' => 'active',
            ]);
        }

        $enrolled = $student->enrollments()->where('status', 'enrolled')->count();
        $completed = $student->learningProgress()->where('progress_percent', 100)->count();
        $pendingFees = $student->fees()->where('status', 'pending')->count();
        $gradesCollection = $student->enrollments()
            ->with('grade', 'course')
            ->get()
            ->filter(fn ($e) =>
                $e->grade &&
                $e->course &&
                $e->grade->midterm_grade !== null &&
                $e->grade->final_grade !== null &&
                $e->course->units !== null
            );
        $avgGrade = $gradesCollection->isEmpty()
            ? null
            : $gradesCollection->map(fn ($e) => ($e->grade->midterm_grade + $e->grade->final_grade) / 2)->avg();
        $totalUnits = $gradesCollection->sum(fn ($e) => (float) ($e->course->units ?? 0));
        $weightedSum = $gradesCollection->sum(fn ($e) => (($e->grade->midterm_grade + $e->grade->final_grade) / 2) * (float) ($e->course->units ?? 0));
        $gwa = $totalUnits > 0 ? round($weightedSum / $totalUnits, 2) : null;
        $studyTimeMinutes = $student->learningProgress()->sum('time_spent_minutes');
        $materialsStarted = $student->learningProgress()->count();
        $credentialsPending = $student->credentialRequests()->whereIn('status', ['pending', 'processing'])->count();
        $unreadMessages = $user->receivedMessages()->whereNull('read_at')->count();

        // Academic load validation: current enrolled units this term
        $currentEnrollments = $student->enrollments()->where('status', 'enrolled')->with('course')->get();
        $currentUnits = $currentEnrollments->sum(fn ($e) => (float) ($e->course->units ?? 0));
        $maxUnits = (int) Setting::getValue('max_units_per_semester', config('app.max_units_per_semester', 24));
        $loadValid = ! $student->isSeniorHigh() || ($currentUnits <= $maxUnits);

        $kpis = [
            'enrolled_courses' => $enrolled,
            'materials_completed' => $completed,
            'materials_started' => $materialsStarted,
            'average_grade' => $avgGrade,
            'gwa' => $gwa,
            'pending_fees' => $pendingFees,
            'study_time_minutes' => $studyTimeMinutes,
            'credentials_pending' => $credentialsPending,
            'unread_messages' => $unreadMessages,
            'current_units' => $currentUnits,
            'max_units' => $maxUnits,
            'load_valid' => $loadValid,
        ];

        $widgetPreferences = optional($user->dashboardPreference)->widgets ?? [];
        $widgetCards = collect(self::KPI_WIDGETS)->map(function ($label, $key) use ($kpis, $widgetPreferences) {
            $config = $widgetPreferences[$key] ?? ['enabled' => true, 'order' => array_search($key, array_keys(self::KPI_WIDGETS), true) + 1];

            return [
                'key' => $key,
                'label' => $label,
                'value' => $kpis[$key],
                'enabled' => $config['enabled'] ?? true,
                'order' => $config['order'] ?? array_search($key, array_keys(self::KPI_WIDGETS), true) + 1,
            ];
        })->filter(fn ($widget) => $widget['enabled'])->sortBy('order')->values()->all();

        return view('student.dashboard', compact('student', 'kpis', 'widgetCards'));
    }
}
