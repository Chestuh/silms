<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningOutcome extends Model
{
    use HasFactory;

    protected $table = 'learning_outcomes';

    protected $fillable = [
        'course_id',
        'code',
        'title',
        'description',
        'bloom_level',
        'assessment_criteria',
    ];

    protected function casts(): array
    {
        return [
            'assessment_criteria' => 'json',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function alignments(): HasMany
    {
        return $this->hasMany(CourseCurriculumAlignment::class);
    }

    /**
     * Get all learning materials aligned with this outcome
     */
    public function getLearningMaterials()
    {
        return $this->alignments()
            ->with('learningMaterial')
            ->get()
            ->pluck('learningMaterial');
    }
}
