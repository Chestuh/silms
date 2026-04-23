<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;
use App\Traits\HasCompletionStatus;
use App\Traits\TrackableActivity;

class LearningMaterial extends Model
{
    use HasFactory, HasCompletionStatus, TrackableActivity;

    protected $table = 'learning_materials';

    protected $fillable = [
        'course_id', 'title', 'description', 'format', 'file_path', 'url',
        'difficulty_level', 'order_index', 'archived', 'approval_status', 'admin_comment', 'completion_status',
        'release_date'
    ];

    protected function casts(): array
    {
        return [
            'archived' => 'boolean',
            'release_date' => 'datetime',
        ];
    }

    public function isReleased(): bool
    {
        if (!$this->release_date) {
            return true; // No release date = always available
        }
        return now()->greaterThanOrEqualTo($this->release_date);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function learningProgress(): HasMany
    {
        return $this->hasMany(LearningProgress::class, 'material_id');
    }

    public function materialRatings(): HasMany
    {
        return $this->hasMany(MaterialRating::class, 'material_id');
    }

    // New relationships for AI features

    public function learningAids(): HasMany
    {
        return $this->hasMany(LearningAid::class, 'material_id');
    }

    public function autoLearningAids(): HasMany
    {
        return $this->hasMany(AutoLearningAid::class, 'material_id');
    }

    public function curriculumAlignments(): HasMany
    {
        return $this->hasMany(CourseCurriculumAlignment::class, 'learning_material_id');
    }

    public function assessmentTemplates(): HasMany
    {
        return $this->hasMany(AssessmentTemplate::class, 'learning_material_id');
    }
}
