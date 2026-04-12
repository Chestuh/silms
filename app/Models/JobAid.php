<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class JobAid extends Model
{
    use HasFactory;

    protected $table = 'job_aids';

    protected $fillable = [
        'student_id',
        'course_id',
        'title',
        'description',
        'aid_type',
        'content',
        'metadata',
        'topic_focus',
        'career_connections',
        'relevance_score',
        'last_viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'metadata' => 'json',
            'career_connections' => 'json',
            'last_viewed_at' => 'datetime',
        ];
    }

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    /**
     * Increment view count
     */
    public function recordView(): void
    {
        $this->increment('views');
        $this->update(['last_viewed_at' => now()]);
    }

    /**
     * Mark as useful
     */
    public function markAsUseful(): void
    {
        $this->increment('useful_count');
    }

    /**
     * Get usefulness rating
     */
    public function getUsefulnessRating(): float
    {
        return $this->views > 0 ? ($this->useful_count / $this->views) * 100 : 0;
    }

    /**
     * Get career connections as array
     */
    public function getCareerConnections(): array
    {
        return $this->career_connections ?? [];
    }
}
