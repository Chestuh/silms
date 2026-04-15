<!-- Completion Status Summary Card Component -->
<!-- Usage: @include('components.completion-status-card', ['entity' => $course]) -->

@php
    $statusClass = match($entity->completion_status ?? null) {
        'completed' => 'success',
        'in_progress' => 'info', 
        'pending' => 'warning',
        default => 'secondary',
    };
    
    $statusLabel = match($entity->completion_status ?? null) {
        'completed' => 'Completed',
        'in_progress' => 'In Progress',
        'pending' => 'Pending',
        default => 'Unknown',
    };
    
    $percentage = match($entity->completion_status ?? null) {
        'completed' => 100,
        'in_progress' => 50,
        'pending' => 0,
        default => 0,
    };
@endphp

<div class="card border-0 shadow-sm h-100">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-start mb-3">
            <div>
                <h6 class="mb-1">{{ $entity->title ?? 'Untitled' }}</h6>
                @if($entity->code ?? false)
                    <small class="text-muted">{{ $entity->code }}</small>
                @endif
            </div>
            <span class="badge bg-{{ $statusClass }}">{{ $statusLabel }}</span>
        </div>
        
        <div class="progress" style="height: 8px;">
            <div class="progress-bar bg-{{ $statusClass }}" style="width: {{ $percentage }}%"></div>
        </div>
        
        @if($entity->completion_status === 'in_progress')
            <small class="text-info d-block mt-2">
                <i class="bi bi-hourglass-split me-1"></i>In progress
            </small>
        @elseif($entity->completion_status === 'completed')
            <small class="text-success d-block mt-2">
                <i class="bi bi-check-circle me-1"></i>All tasks completed
            </small>
        @else
            <small class="text-muted d-block mt-2">
                <i class="bi bi-clock me-1"></i>Not started
            </small>
        @endif
    </div>
</div>
