<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class StudentProgressAnalytic extends Model
{
    use HasFactory;

    protected $table = 'student_progress_analytics';

    protected $fillable = [
        'student_id',
        'course_id',
        'completion_rate',
        'materials_completed',
        'materials_total',
        'total_time_minutes',
        'average_rating',
        'current_grade',
        'quiz_average',
        'assessment_average',
        'at_risk_status',
        'weak_topics',
        'strong_topics',
        'recommendations',
        'last_analyzed_at',
    ];

    protected function casts(): array
    {
        return [
            'weak_topics' => 'json',
            'strong_topics' => 'json',
            'last_analyzed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Determine if student is at risk based on current metrics
     */
    public function calculateAtRiskStatus(): string
    {
        $riskScore = 0;

        // Grade threshold: < 75 = needs improvement
        if ($this->current_grade && $this->current_grade < 75) {
            $riskScore += 2;
        }

        // Quiz average: < 70 = weak area
        if ($this->quiz_average && $this->quiz_average < 70) {
            $riskScore += 2;
        }

        // Completion rate: < 50% = falling behind
        if ($this->completion_rate < 50) {
            $riskScore += 2;
        }

        // Assignment average: < 60 = concerning
        if ($this->assessment_average && $this->assessment_average < 60) {
            $riskScore += 3;
        }

        // Determine status
        if ($riskScore >= 6) {
            return 'critical';
        } elseif ($riskScore >= 4) {
            return 'at_risk';
        } else {
            return 'on_track';
        }
    }

    /**
     * Update analytics from actual data
     */
    public static function updateAnalyticsForStudent(Student $student, Course $course)
    {
        // Get or create analytics record
        $analytics = self::firstOrCreate(
            ['student_id' => $student->id, 'course_id' => $course->id]
        );

        // Calculate metrics from learning progress
        $enrollment = $student->enrollments()
            ->where('course_id', $course->id)
            ->first();

        if ($enrollment) {
            // Get learning materials for this course
            $materials = $course->learningMaterials()->get();
            $analytics->materials_total = $materials->count();

            // Get student's progress on these materials
            $progress = $student->learningProgress()
                ->whereIn('material_id', $materials->pluck('id'))
                ->get();

            $analytics->materials_completed = $progress->where('progress_percent', 100)->count();
            $analytics->completion_rate = $materials->count() > 0 
                ? ($analytics->materials_completed / $materials->count()) * 100 
                : 0;

            $analytics->total_time_minutes = $progress->sum('time_spent_minutes');
            $analytics->average_rating = $progress
                ->mapWithKeys(fn($p) => [$p->material_id => $p->material->materialRatings()->avg('rating')])
                ->values()
                ->average() ?? 0;

            // Get grades
            $grades = $enrollment->grade;
            if ($grades) {
                $analytics->current_grade = $grades->final_grade ?? $grades->midterm_grade;
            }

            // Update risk status
            $analytics->at_risk_status = $analytics->calculateAtRiskStatus();
            $analytics->last_analyzed_at = now();
            $analytics->save();
        }

        return $analytics;
    }
}
