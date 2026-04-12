<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningMaterial extends Model
{
    use HasFactory;

    protected $table = 'learning_materials';

    protected $fillable = [
        'course_id', 'title', 'description', 'format', 'file_path', 'url',
        'difficulty_level', 'order_index', 'archived', 'approval_status', 'admin_comment',
    ];

    protected function casts(): array
    {
        return ['archived' => 'boolean'];
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
