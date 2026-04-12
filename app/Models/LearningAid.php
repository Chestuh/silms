<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class LearningAid extends Model
{
    use HasFactory;

    protected $table = 'learning_aids';

    protected $fillable = [
        'material_id',
        'course_id',
        'aid_type',
        'content',
        'metadata',
        'generation_tokens_used',
        'generated_at',
    ];

    protected function casts(): array
    {
        return [
            'generated_at' => 'datetime',
            'last_updated_at' => 'datetime',
            'metadata' => 'json',
        ];
    }

    public function material(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class, 'material_id');
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function interactions(): HasMany
    {
        return $this->hasMany(LearningAidInteraction::class);
    }

    /**
     * Get interaction statistics
     */
    public function getInteractionStats()
    {
        return $this->interactions()
            ->selectRaw('COUNT(*) as total_views, AVG(time_spent_seconds) as avg_time_spent')
            ->first();
    }
}
