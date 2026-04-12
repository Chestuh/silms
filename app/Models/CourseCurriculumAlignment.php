<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class CourseCurriculumAlignment extends Model
{
    use HasFactory;

    protected $table = 'course_curriculum_alignments';

    protected $fillable = [
        'course_id',
        'learning_material_id',
        'learning_outcome_id',
        'curriculum_standard_id',
        'competency',
        'alignment_strength',
        'alignment_notes',
    ];

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function learningMaterial(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class);
    }

    public function learningOutcome(): BelongsTo
    {
        return $this->belongsTo(LearningOutcome::class);
    }

    public function curriculumStandard(): BelongsTo
    {
        return $this->belongsTo(CurriculumStandard::class);
    }

    /**
     * Get alignment strength as percentage
     */
    public function getStrengthPercentage()
    {
        return ($this->alignment_strength / 5) * 100;
    }
}
