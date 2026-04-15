<?php

namespace App\Traits;

use Illuminate\Database\Eloquent\Builder;

trait TrackableActivity
{
    /**
     * Check if activity is completed (100% progress)
     */
    public function isActivityCompleted(): bool
    {
        if (method_exists($this, 'learningProgress')) {
            $progress = $this->learningProgress()->first();
            return $progress && $progress->progress_percent === 100 && $progress->completed_at !== null;
        }
        return false;
    }

    /**
     * Check if activity is started (has any progress)
     */
    public function isActivityStarted(): bool
    {
        if (method_exists($this, 'learningProgress')) {
            $progress = $this->learningProgress()->first();
            return $progress && $progress->progress_percent > 0;
        }
        return false;
    }

    /**
     * Get the overall activity completion status
     */
    public function getActivityCompletionStatus(): string
    {
        if ($this->isActivityCompleted()) {
            return 'completed';
        } elseif ($this->isActivityStarted()) {
            return 'in_progress';
        }
        return 'pending';
    }

    /**
     * Scope to get activities by completion status
     */
    public function scopeByActivityStatus(Builder $query, string $status): Builder
    {
        return match($status) {
            'completed' => $query->whereHas('learningProgress', function ($q) {
                $q->where('progress_percent', 100)->whereNotNull('completed_at');
            }),
            'in_progress' => $query->whereHas('learningProgress', function ($q) {
                $q->whereBetween('progress_percent', [1, 99])->orWhere(function ($q) {
                    $q->where('progress_percent', 100)->whereNull('completed_at');
                });
            }),
            'pending' => $query->whereDoesntHave('learningProgress')->orWhereHas('learningProgress', function ($q) {
                $q->where('progress_percent', 0);
            }),
            default => $query,
        };
    }
}
