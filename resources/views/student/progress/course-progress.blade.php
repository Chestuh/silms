@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-8">
            <h1>{{ $course->title }} - Detailed Analytics</h1>
            <p class="text-muted">Your complete performance breakdown</p>
        </div>
        <div class="col-md-4 text-right">
            <a href="{{ route('student.progress.analytics') }}" class="btn btn-outline-secondary">
                ← Back to Dashboard
            </a>
        </div>
    </div>

    <!-- Key Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card text-center border-primary">
                <div class="card-body">
                    <h6 class="text-muted">Completion Rate</h6>
                    <div class="h2">{{ number_format($analytics->completion_rate, 1) }}%</div>
                    <small class="text-muted">{{ $analytics->materials_completed }}/{{ $analytics->materials_total }} materials</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-success">
                <div class="card-body">
                    <h6 class="text-muted">Current Grade</h6>
                    <div class="h2">
                        @if($analytics->current_grade)
                            {{ number_format($analytics->current_grade, 2) }}
                        @else
                            —
                        @endif
                    </div>
                    <small class="text-muted">Last recorded</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-warning">
                <div class="card-body">
                    <h6 class="text-muted">Quiz Average</h6>
                    <div class="h2">
                        @if($analytics->quiz_average)
                            {{ number_format($analytics->quiz_average, 2) }}%
                        @else
                            —
                        @endif
                    </div>
                    <small class="text-muted">Self-assessments</small>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card text-center border-info">
                <div class="card-body">
                    <h6 class="text-muted">Time Spent</h6>
                    <div class="h2">{{ number_format($analytics->total_time_minutes / 60, 1) }}h</div>
                    <small class="text-muted">Total study time</small>
                </div>
            </div>
        </div>
    </div>

    <!-- Status & Recommendations -->
    <div class="row mb-4">
        <div class="col-md-8">
            <div class="card">
                <div class="card-header bg-{{ $analytics->at_risk_status === 'critical' ? 'danger' : ($analytics->at_risk_status === 'at_risk' ? 'warning' : 'success') }} text-white">
                    <h6 class="mb-0">📊 Status: {{ ucfirst($analytics->at_risk_status) }}</h6>
                </div>
                <div class="card-body">
                    @if($analytics->recommendations)
                        <h6>Recommended Actions:</h6>
                        <ul>
                            @foreach(explode("\n", $analytics->recommendations) as $rec)
                                @if(trim($rec))
                                    <li>{{ trim($rec) }}</li>
                                @endif
                            @endforeach
                        </ul>
                    @else
                        <p class="text-muted">No specific recommendations available yet. Keep up the good work!</p>
                    @endif
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">💡 Suggested Actions</h6>
                </div>
                <div class="card-body">
                    <a href="{{ route('student.learning-aids.index', ['course_id' => $course->id]) }}" class="btn btn-sm btn-info btn-block mb-2">
                        📚 Learning Aids
                    </a>
                    <a href="{{ route('student.job-aids.index') }}" class="btn btn-sm btn-secondary btn-block">
                        🎯 Get Job Aids
                    </a>
                </div>
            </div>
        </div>
    </div>

    <!-- Materials Breakdown -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header">
                    <h6 class="mb-0">📖 Learning Materials Breakdown</h6>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-sm">
                            <thead>
                                <tr>
                                    <th>Material</th>
                                    <th class="text-center" style="width: 150px;">Progress</th>
                                    <th class="text-center" style="width: 100px;">Time</th>
                                    <th class="text-center" style="width: 100px;">Rating</th>
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($materialBreakdown as $item)
                                    <tr>
                                        <td>{{ $item['material']->title }}</td>
                                        <td>
                                            <div class="progress" style="height: 20px;">
                                                <div class="progress-bar" role="progressbar" 
                                                     style="width: {{ $item['progress'] }}%"
                                                     aria-valuenow="{{ $item['progress'] }}" aria-valuemin="0" aria-valuemax="100">
                                                    {{ $item['progress'] }}%
                                                </div>
                                            </div>
                                        </td>
                                        <td class="text-center">
                                            {{ $item['time_spent'] }} min
                                        </td>
                                        <td class="text-center">
                                            @if($item['rating'])
                                                <span class="badge badge-success">{{ number_format($item['rating'], 1) }} ⭐</span>
                                            @else
                                                <span class="text-muted">—</span>
                                            @endif
                                        </td>
                                        <td class="text-right">
                                            <a href="{{ route('student.learning.material', $item['material']) }}" class="btn btn-sm btn-outline-primary">
                                                View
                                            </a>
                                        </td>
                                    </tr>
                                @empty
                                    <tr>
                                        <td colspan="5" class="text-center text-muted py-4">
                                            No materials in this course yet.
                                        </td>
                                    </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
