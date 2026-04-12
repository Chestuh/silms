<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class LearningAidInteraction extends Model
{
    use HasFactory;

    protected $table = 'learning_aid_interactions';

    protected $fillable = [
        'student_id',
        'learning_aid_id',
        'interaction_type',
        'time_spent_seconds',
        'quiz_score',
        'notes',
    ];

    public function student(): BelongsTo
    {
        return $this->belongsTo(Student::class);
    }

    public function learningAid(): BelongsTo
    {
        return $this->belongsTo(LearningAid::class);
    }

    /**
     * Check if student scored above passing on quiz
     */
    public function isQuizPassed(float $passingScore = 70): bool
    {
        return $this->quiz_score && $this->quiz_score >= $passingScore;
    }
}
