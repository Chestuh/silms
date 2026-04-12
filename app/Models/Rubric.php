<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Rubric extends Model
{
    protected $fillable = ['material_id', 'course_id', 'name', 'criteria_json'];

    protected $casts = [
        'criteria_json' => 'array',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }
}
