<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AutoLearningAid extends Model
{
    use HasFactory;

    protected $table = 'auto_learning_aids';

    protected $fillable = [
        'material_id',
        'instructor_id',
        'course_id',
        'title',
        'description',
        'file_path',
        'release_at',
        'status',
    ];

    protected $casts = [
        'release_at' => 'datetime',
    ];

    public function material(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(User::class, 'instructor_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class, 'course_id');
    }

    public function getIsAvailableAttribute(): bool
    {
        return $this->release_at && $this->release_at->isPast() && $this->status !== 'draft';
    }
}
