<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningPathRule extends Model
{
    protected $fillable = [
        'type', 'name', 'source_course_id', 'target_course_id',
        'source_material_id', 'target_material_id', 'sort_order', 'is_active',
    ];

    protected function casts(): array
    {
        return ['is_active' => 'boolean'];
    }

    public function sourceCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'source_course_id');
    }

    public function targetCourse(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'target_course_id');
    }

    public function sourceMaterial(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'source_material_id');
    }

    public function targetMaterial(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'target_material_id');
    }

    public static function typeOptions(): array
    {
        return [
            'course_prerequisite' => 'Complete Course A before Course B',
            'material_prerequisite' => 'Complete Material A before Material B',
            'difficulty_order' => 'Recommend easy → medium → hard',
        ];
    }
}
