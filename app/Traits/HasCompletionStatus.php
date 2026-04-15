<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait HasCompletionStatus
{
    /**
     * Mark the entity as completed
     */
    public function markAsCompleted(): void
    {
        $this->update(['completion_status' => 'completed']);
    }

    /**
     * Mark the entity as in progress
     */
    public function markAsInProgress(): void
    {
        $this->update(['completion_status' => 'in_progress']);
    }

    /**
     * Mark the entity as pending
     */
    public function markAsPending(): void
    {
        $this->update(['completion_status' => 'pending']);
    }

    /**
     * Check if the entity is completed
     */
    public function isCompleted(): bool
    {
        return $this->completion_status === 'completed';
    }

    /**
     * Check if the entity is in progress
     */
    public function isInProgress(): bool
    {
        return $this->completion_status === 'in_progress';
    }

    /**
     * Check if the entity is pending
     */
    public function isPending(): bool
    {
        return $this->completion_status === 'pending';
    }

    /**
     * Scope to filter by completed entities
     */
    public function scopeCompleted(Builder $query): Builder
    {
        return $query->where('completion_status', 'completed');
    }

    /**
     * Scope to filter by in-progress entities
     */
    public function scopeInProgress(Builder $query): Builder
    {
        return $query->where('completion_status', 'in_progress');
    }

    /**
     * Scope to filter by pending entities
     */
    public function scopePending(Builder $query): Builder
    {
        return $query->where('completion_status', 'pending');
    }

    /**
     * Get completion percentage for progress display
     */
    public function getCompletionPercentage(): int
    {
        return match($this->completion_status) {
            'completed' => 100,
            'in_progress' => 50,
            'pending' => 0,
            default => 0,
        };
    }

    /**
     * Get badge class for display
     */
    public function getCompletionBadgeClass(): string
    {
        return match($this->completion_status) {
            'completed' => 'bg-success',
            'in_progress' => 'bg-info',
            'pending' => 'bg-warning',
            default => 'bg-secondary',
        };
    }

    /**
     * Get readable status label
     */
    public function getCompletionStatusLabel(): string
    {
        return match($this->completion_status) {
            'completed' => 'Completed',
            'in_progress' => 'In Progress',
            'pending' => 'Pending',
            default => 'Unknown',
        };
    }
}
