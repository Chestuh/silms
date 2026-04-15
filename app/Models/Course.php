<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCompletionStatus;

class Course extends Model
{
    use HasFactory, HasCompletionStatus;

    protected $fillable = ['code', 'title', 'grade_level', 'units', 'semester', 'instructor_id', 'completion_status'];

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    public function enrollments(): HasMany
    {
        return $this->hasMany(Enrollment::class);
    }

    public function learningMaterials(): HasMany
    {
        return $this->hasMany(LearningMaterial::class, 'course_id');
    }

    // New relationships for AI features

    public function learningAids(): HasMany
    {
        return $this->hasMany(LearningAid::class);
    }

    public function curriculumAlignments(): HasMany
    {
        return $this->hasMany(CourseCurriculumAlignment::class);
    }

    public function learningOutcomes(): HasMany
    {
        return $this->hasMany(LearningOutcome::class);
    }

    public function assessmentTemplates(): HasMany
    {
        return $this->hasMany(AssessmentTemplate::class);
    }

    public function studentProgressAnalytics(): HasMany
    {
        return $this->hasMany(StudentProgressAnalytic::class);
    }
}
