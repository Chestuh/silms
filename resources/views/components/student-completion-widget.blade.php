<!-- Student Completion Status Widget for Dashboard -->
<!-- Usage: @include('components.student-completion-widget', ['student' => $student]) -->

@php
    $completionService = app(\App\Services\AcademicCompletionStatusService::class);
    $summary = $completionService->getStudentCompletionSummary($student);
@endphp

<div class="row mb-4">
    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-book text-info" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $summary['courses']['completed'] }}/{{ $summary['courses']['total'] }}</h3>
                <small class="text-muted">Courses Completed</small>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-info" style="width: {{ $summary['courses']['completion_percentage'] }}%"></div>
                </div>
                <small class="d-block mt-2 text-muted">{{ $summary['courses']['completion_percentage'] }}%</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-list-check text-warning" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $summary['activities']['completed'] }}/{{ $summary['activities']['total'] }}</h3>
                <small class="text-muted">Activities Completed</small>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-warning" style="width: {{ $summary['activities']['completion_percentage'] }}%"></div>
                </div>
                <small class="d-block mt-2 text-muted">{{ $summary['activities']['completion_percentage'] }}%</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-credit-card text-success" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $summary['payments']['paid'] }}/{{ $summary['payments']['total'] }}</h3>
                <small class="text-muted">Payments Completed</small>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar bg-success" style="width: {{ $summary['payments']['completion_percentage'] }}%"></div>
                </div>
                <small class="d-block mt-2 text-muted">{{ $summary['payments']['completion_percentage'] }}%</small>
            </div>
        </div>
    </div>

    <div class="col-md-3">
        <div class="card border-0 shadow-sm">
            <div class="card-body text-center">
                <i class="bi bi-graph-up text-{{ $summary['overall']['badge_class'] === 'bg-success' ? 'success' : ($summary['overall']['badge_class'] === 'bg-info' ? 'info' : 'warning') }}" style="font-size: 2rem;"></i>
                <h3 class="mt-2">{{ $summary['overall']['overall_percentage'] }}%</h3>
                <small class="text-muted">Overall Progress</small>
                <div class="progress mt-2" style="height: 6px;">
                    <div class="progress-bar {{ $summary['overall']['badge_class'] }}" style="width: {{ $summary['overall']['overall_percentage'] }}%"></div>
                </div>
                <small class="d-block mt-2 text-muted badge {{ $summary['overall']['badge_class'] }}">{{ $summary['overall']['status'] }}</small>
            </div>
        </div>
    </div>
</div>
