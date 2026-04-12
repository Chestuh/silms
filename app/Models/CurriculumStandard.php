<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;

class CurriculumStandard extends Model
{
    use HasFactory;

    protected $table = 'curriculum_standards';

    protected $fillable = [
        'code',
        'title',
        'description',
        'subject_area',
        'grade_level',
        'competencies',
    ];

    protected function casts(): array
    {
        return [
            'competencies' => 'json',
        ];
    }

    public function alignments(): HasMany
    {
        return $this->hasMany(CourseCurriculumAlignment::class);
    }

    /**
     * Get all learning materials aligned with this standard
     */
    public function getLearningMaterials()
    {
        return $this->alignments()
            ->with('learningMaterial')
            ->get()
            ->pluck('learningMaterial');
    }
}
