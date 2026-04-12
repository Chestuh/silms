<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class AssessmentTemplate extends Model
{
    use HasFactory;

    protected $table = 'assessment_templates';

    protected $fillable = [
        'course_id',
        'learning_material_id',
        'instructor_id',
        'title',
        'description',
        'assessment_type',
        'number_of_questions',
        'passing_score',
        'time_limit_minutes',
        'questions_json',
        'status',
        'instructor_feedback',
        'approved_at',
        'reviewed_at',
        'generation_metadata',
        'ai_generated',
    ];

    protected function casts(): array
    {
        return [
            'questions_json' => 'json',
            'generation_metadata' => 'json',
            'approved_at' => 'datetime',
            'reviewed_at' => 'datetime',
        ];
    }

    public function course(): BelongsTo
    {
        return $this->belongsTo(Course::class);
    }

    public function learningMaterial(): BelongsTo
    {
        return $this->belongsTo(LearningMaterial::class);
    }

    public function instructor(): BelongsTo
    {
        return $this->belongsTo(Instructor::class);
    }

    /**
     * Check if assessment is approved
     */
    public function isApproved(): bool
    {
        return $this->status === 'approved' || $this->status === 'published';
    }

    /**
     * Check if assessment is pending review
     */
    public function isPendingReview(): bool
    {
        return $this->status === 'pending_review';
    }

    /**
     * Approve the assessment
     */
    public function approve(string $feedback = null): void
    {
        $this->status = 'approved';
        $this->approved_at = now();
        $this->reviewed_at = now();
        if ($feedback) {
            $this->instructor_feedback = $feedback;
        }
        $this->save();
    }

    /**
     * Reject the assessment
     */
    public function reject(string $feedback): void
    {
        $this->status = 'rejected';
        $this->reviewed_at = now();
        $this->instructor_feedback = $feedback;
        $this->save();
    }

    /**
     * Publish the assessment (make it available to students)
     */
    public function publish(): void
    {
        if ($this->isApproved()) {
            $this->status = 'published';
            $this->save();
        }
    }

    /**
     * Get questions as array
     */
    public function getQuestions(): array
    {
        return $this->questions_json ?? [];
    }

    /**
     * Add a question to the assessment
     */
    public function addQuestion(array $question): void
    {
        $questions = $this->getQuestions();
        $questions[] = $question;
        $this->questions_json = $questions;
        $this->number_of_questions = count($questions);
        $this->save();
    }
}
