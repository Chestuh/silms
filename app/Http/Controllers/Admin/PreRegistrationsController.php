<?php

namespace App\Http\Controllers\Admin;

use App\Models\PreRegistration;
use App\Models\User;
use App\Models\Student;
use App\Models\Enrollment;
use App\Models\Course;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\DB;
use App\Mail\PreRegistrationApproved;
use App\Mail\PreRegistrationRejected;
use Illuminate\Http\Request;
use App\Http\Controllers\Controller;

class PreRegistrationsController extends Controller
{
    public function index(Request $request)
    {
        $status = $request->query('status', 'pending');
        $valid_statuses = ['pending', 'approved', 'rejected'];
        
        if (!in_array($status, $valid_statuses)) {
            $status = 'pending';
        }

        $preRegistrations = PreRegistration::where('status', $status)
            ->orderBy('created_at', 'desc')
            ->paginate(15);

        $counts = [];
        foreach ($valid_statuses as $s) {
            $counts[$s] = PreRegistration::where('status', $s)->count();
        }

        return view('admin.pre-registrations.index', [
            'preRegistrations' => $preRegistrations,
            'status' => $status,
            'counts' => $counts,
        ]);
    }

    public function show(PreRegistration $preRegistration)
    {
        return view('admin.pre-registrations.show', [
            'preRegistration' => $preRegistration,
        ]);
    }

    public function approve(PreRegistration $preRegistration)
    {
        if ($preRegistration->status !== 'pending') {
            return back()->with('error', 'Can only approve pending pre-registrations.');
        }

        // Create user account with already-hashed password using raw insert to avoid double-hashing
        $userId = DB::table('users')->insertGetId([
            'email' => $preRegistration->email,
            'password' => $preRegistration->password_hash,
            'role' => 'student',
            'name' => $preRegistration->full_name,
            'email_verified_at' => now(),
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        $user = User::find($userId);

        // Generate school ID
        $current_year = 2027;
        $prefix = $current_year . '-';
        
        $lastStudent = Student::where('school_id', 'like', $prefix . '%')
            ->orderBy('school_id', 'desc')
            ->first();

        if ($lastStudent) {
            $lastNumber = (int)substr($lastStudent->school_id, strlen($prefix));
            $nextNumber = $lastNumber + 1;
        } else {
            $nextNumber = 1;
        }

        $schoolId = $prefix . str_pad($nextNumber, 5, '0', STR_PAD_LEFT);

        // Determine program value based on the applicant's year level.
        // For Grade 11-12 use the preRegistration's preferred_program when available.
        // For Grade 7-10 leave program null (no program applies).
        $programValue = null;
        $yearLevel = (int) ($preRegistration->year_level ?? 0);
        if ($yearLevel >= 11) {
            $programValue = $preRegistration->preferred_program ?: $preRegistration->program;
        }

        // Create student record
        $student = Student::create([
            'user_id' => $user->id,
            'school_id' => $schoolId,
            'student_number' => $schoolId,
            'program' => $programValue,
            'year_level' => $preRegistration->year_level,
            'admission_date' => now(),
            'status' => 'active',
        ]);

        // Auto-enroll Grade 7 students in all Grade 7 courses
        if ($preRegistration->applicant_category === 'grade7') {
            $month = now()->month;
            $year = now()->year;
            $semester = $month <= 6 ? '1st Semester' : '2nd Semester';
            $schoolYear = $month <= 6 ? ($year - 1) . '-' . $year : $year . '-' . ($year + 1);

            $grade7Courses = Course::where('grade_level', '7')->get();
            foreach ($grade7Courses as $course) {
                Enrollment::create([
                    'student_id' => $student->id,
                    'course_id' => $course->id,
                    'semester' => $semester,
                    'school_year' => $schoolYear,
                    'status' => 'enrolled',
                ]);
            }
        }

        // Update pre-registration status
        $preRegistration->update([
            'status' => 'approved',
            'approved_at' => now(),
        ]);

        // Attach school id to the model instance for email rendering
        $preRegistration->school_id = $schoolId;

        // Send approval email (only if mail driver is configured)
        if (config('mail.driver')) {
            try {
                Mail::to($preRegistration->email)->send(new PreRegistrationApproved($preRegistration));
            } catch (\Exception $e) {
                // Log or continue silently
            }
        }

        return back()->with('success', "Pre-registration approved! Student created with School ID: {$schoolId}. The applicant may now log in using their email and password.");
    }

    public function reject(Request $request, PreRegistration $preRegistration)
    {
        if ($preRegistration->status !== 'pending') {
            return back()->with('error', 'Can only reject pending pre-registrations.');
        }

        $request->validate([
            'rejection_reason' => 'required|string|max:1000',
        ]);

        $preRegistration->update([
            'status' => 'rejected',
            'rejection_reason' => $request->input('rejection_reason'),
            'rejected_at' => now(),
        ]);

        // Send rejection email (only if mail driver is configured)
        if (config('mail.driver')) {
            try {
                Mail::to($preRegistration->email)->send(new PreRegistrationRejected($preRegistration, $request->input('rejection_reason')));
            } catch (\Exception $e) {
                // ignore
            }
        }

        return back()->with('success', 'Pre-registration rejected.');
    }
}
