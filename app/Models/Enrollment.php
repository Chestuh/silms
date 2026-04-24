<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasOne;

class Enrollment extends Model
{
    use HasFactory;

    protected $fillable = ['student_id', 'course_id', 'semester', 'school_year', 'status', 'attachments'];

    protected function casts(): array
    {
        return ['attachments' => 'array'];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function grade(): HasOne
    {
        return $this->hasOne(Grade::class);
    }

    /**
     * Auto-credit all courses in the same grade/department when an enrollment is approved.
     * This generalizes the previous Grade 7-only behavior so that any enrollment
     * will credit all other courses that share the same `grade_level`.
     */
    public function autoCreditDepartmentCourses(): void
    {
        $gradeLevel = $this->course?->grade_level ?? null;
        if (is_null($gradeLevel)) {
            return;
        }

        // Auto-credit all courses for the same grade level
        $courses = Course::where('grade_level', (string) $gradeLevel)->get();

        foreach ($courses as $course) {
            // Skip if it's the same course we're already enrolled in
            if ($course->id === $this->course_id) {
                continue;
            }

            Enrollment::firstOrCreate(
                [
                    'student_id' => $this->student_id,
                    'course_id' => $course->id,
                    'semester' => $this->semester,
                    'school_year' => $this->school_year,
                ],
                ['status' => 'enrolled']
            );
        }
    }

    protected static function booted()
    {
        static::creating(function ($enrollment) {
            // Validate academic load for the student when adding a new enrollment
            try {
                $student = $enrollment->student()->first();
                if ($student && $student->isSeniorHigh()) {
                    $semester = $enrollment->semester;
                    $schoolYear = $enrollment->school_year;
                    $unitsNow = $student->totalUnits($semester, $schoolYear);
                    $courseUnits = (int) ($enrollment->course ? $enrollment->course->units : \App\Models\Course::find($enrollment->course_id)->units ?? 0);
                    $max = config('education.max_units_per_semester', 24);
                    if (($unitsNow + $courseUnits) > $max) {
                        throw new \Exception('Academic load would exceed maximum allowed units ('.$max.').');
                    }
                }
            } catch (\Exception $e) {
                // Re-throw to prevent creation
                throw $e;
            }
        });
    }
}
