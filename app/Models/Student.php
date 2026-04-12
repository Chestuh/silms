<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Illuminate\Support\Facades\DB;

class Student extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id', 'school_id', 'student_number', 'program', 'year_level',
        'admission_date', 'status', 'academic_status',
    ];

    protected function casts(): array
    {
        return [
            'admission_date' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function admissionRecords(): HasMany
    {
        return $this->hasMany(AdmissionRecord::class);
    }

    public function disciplinaryRecords(): HasMany
    {
        return $this->hasMany(DisciplinaryRecord::class);
    }

    public function academicHonors(): HasMany
    {
        return $this->hasMany(AcademicHonor::class);
    }

    public function fees(): HasMany
    {
        return $this->hasMany(Fee::class);
    }

    public function learningProgress(): HasMany
    {
        return $this->hasMany(LearningProgress::class);
    }

    public function materialRatings(): HasMany
    {
        return $this->hasMany(MaterialRating::class);
    }

    public function studyReminders(): HasMany
    {
        return $this->hasMany(StudyReminder::class);
    }

    public function credentialRequests(): HasMany
    {
        return $this->hasMany(CredentialRequest::class);
    }

    public function selfAssessments(): HasMany
    {
        return $this->hasMany(SelfAssessment::class);
    }

    // New relationships for AI features

    public function studentProgressAnalytics(): HasMany
    {
        return $this->hasMany(StudentProgressAnalytic::class);
    }

    public function jobAids(): HasMany
    {
        return $this->hasMany(JobAid::class);
    }

    public function learningAidInteractions(): HasMany
    {
        return $this->hasMany(LearningAidInteraction::class);
    }

    /**
     * Compute General Weighted Average from enrolled subjects and grades.
     */
    public function computeGwa(): ?float
    {
        $enrollments = $this->enrollments()
            ->with(['course', 'grade'])
            ->whereIn('status', ['enrolled', 'completed'])
            ->get();
        $totalUnits = 0;
        $weightedSum = 0;
        foreach ($enrollments as $e) {
            $g = $e->grade;
            if (!$g || $g->midterm_grade === null || $g->final_grade === null) {
                continue;
            }
            $avg = ((float) $g->midterm_grade + (float) $g->final_grade) / 2;
            $u = (float) $e->course->units;
            $totalUnits += $u;
            $weightedSum += $avg * $u;
        }
        return $totalUnits > 0 ? round($weightedSum / $totalUnits, 4) : null;
    }

    /**
     * Get total enrolled units for a semester and school year.
     */
    public function totalUnits(string $semester, string $schoolYear): int
    {
        return (int) $this->enrollments()
            ->where('semester', $semester)
            ->where('school_year', $schoolYear)
            ->with('course')
            ->get()
            ->sum(function ($e) {
                return (int) ($e->course->units ?? 0);
            });
    }

    /**
     * Check whether academic load is valid (uses config/education.php).
     */
    public function isSeniorHigh(): bool
    {
        return in_array((int) $this->year_level, [11, 12], true);
    }

    public function isAcademicLoadValid(string $semester, string $schoolYear): bool
    {
        if (! $this->isSeniorHigh()) {
            return true;
        }

        $units = $this->totalUnits($semester, $schoolYear);
        $max = config('education.max_units_per_semester', 24);
        return $units <= $max;
    }

    /**
     * Return skill gaps for the student.
     * Strategy:
     *  - For enrolled/completed courses, check grade averages and learning progress completion.
     *  - Return array of ['course_code'=>'reason'] for simple reporting.
     */
    public function skillGaps(string $semester = null, string $schoolYear = null): array
    {
        $gradeThreshold = config('education.skill_gap_grade_threshold', 2.5);
        $completionThreshold = config('education.skill_gap_completion_threshold', 60);

        $enrollments = $this->enrollments()->with(['course', 'grade'])->get();
        $gaps = [];

        foreach ($enrollments as $e) {
            $course = $e->course;
            if (!$course) {
                continue;
            }

            // Check grade-based gap
            $gapReasons = [];
            if ($e->grade) {
                $g = $e->grade;
                if ($g->midterm_grade !== null && $g->final_grade !== null) {
                    $avg = (($g->midterm_grade + $g->final_grade) / 2.0);
                    if ($avg >= $gradeThreshold) {
                        $gapReasons[] = 'Low grade (avg '.round($avg,2).')';
                    }
                }
            }

            // Check learning progress completion for this course's materials
            $completedPercent = 100;
            try {
                $total = \App\Models\LearningMaterial::where('course_id', $course->id)->count();
                if ($total > 0) {
                    $completed = \App\Models\LearningProgress::where('student_id', $this->id)
                        ->whereHas('material', function ($q) use ($course) { $q->where('course_id', $course->id); })
                        ->where('progress_percent', '>=', 100)
                        ->count();
                    $completedPercent = (int) round(($completed / $total) * 100);
                    if ($completedPercent < $completionThreshold) {
                        $gapReasons[] = 'Low material completion ('.$completedPercent.'%)';
                    }
                }
            } catch (\Throwable $t) {
                // ignore missing tables or relations in minimal environments
            }

            if (!empty($gapReasons)) {
                $gaps[$course->code ?? 'course_'.$course->id] = implode('; ', $gapReasons);
            }
        }

        return $gaps;
    }

    /**
     * Get academic status: passed, failed, inc, drop.
     * - Stored academic_status values are mapped from old values.
     * - drop/incomplete enrollment statuses are prioritized.
     * - No GWA and no dropped enrollment => inc; 0.00 => inc; GWA < 75 => failed; otherwise passed.
     */
    public function getResolvedAcademicStatus(): string
    {
        if ($this->academic_status) {
            $status = strtolower($this->academic_status);
            return match ($status) {
                'regular', 'passed' => 'passed',
                'irregular', 'failed' => 'failed',
                'probationary', 'at-risk' => 'failed',
                'inc', 'incomplete' => 'inc',
                'drop', 'dropped' => 'drop',
                default => $status,
            };
        }

        // If student currently has dropped or incomplete enrollments, use those statuses directly
        if ($this->enrollments()->where('status', 'dropped')->exists()) {
            return 'drop';
        }
        if ($this->enrollments()->where('status', 'incomplete')->exists()) {
            return 'inc';
        }

        $gwa = $this->computeGwa();

        // No GWA available => INC by default when the student is not dropped
        if ($gwa === null) {
            return 'inc';
        }

        // Exactly 0.00 => INC
        if ((float) $gwa === 0.0) {
            return 'inc';
        }

        // Below 75.00 => Failed
        if ($gwa < 75.0) {
            return 'failed';
        }

        // 75.00 or higher => Passed
        return 'passed';
    }

    /**
     * Ensure the student has a student_number. If missing, assign one using
     * the current year prefix and a zero-padded sequence (e.g. "2026 - 00001").
     */
    public function ensureStudentNumber(): void
    {
        if (!empty($this->student_number)) {
            return;
        }

        $year = now()->year;
        $prefix = $year . ' - ';

        $maxSeq = DB::table('students')
            ->where('student_number', 'like', $prefix.'%')
            ->selectRaw("MAX(CAST(SUBSTRING_INDEX(student_number, ' - ', -1) AS UNSIGNED)) as max_seq")
            ->value('max_seq');

        $next = ((int) $maxSeq) + 1;
        $formatted = str_pad($next, 5, '0', STR_PAD_LEFT);

        $this->update(['student_number' => $prefix . $formatted]);
    }
}
