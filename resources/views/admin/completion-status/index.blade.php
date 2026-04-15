@extends('layouts.app')

@section('title', 'Academic Completion Status')

@section('content')
<div class="container-fluid">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2><i class="bi bi-check-circle me-2"></i>Academic Completion Status</h2>
            <p class="text-muted">Track completion of courses, modules, activities, and payments</p>
        </div>
    </div>

    @if(isset($student))
        <!-- Student Completion Summary -->
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted text-uppercase fw-bold">Course Completion</small>
                                <h3 class="mt-2">{{ $completionSummary['courses']['completed'] }}/{{ $completionSummary['courses']['total'] }}</h3>
                            </div>
                            <span class="badge {{ $completionSummary['courses']['completion_percentage'] === 100 ? 'bg-success' : 'bg-info' }}">
                                {{ $completionSummary['courses']['completion_percentage'] }}%
                            </span>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar {{ $completionSummary['courses']['completion_percentage'] === 100 ? 'bg-success' : 'bg-info' }}" 
                                 style="width: {{ $completionSummary['courses']['completion_percentage'] }}%"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-dash-circle me-1"></i>{{ $completionSummary['courses']['in_progress'] }} in progress
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted text-uppercase fw-bold">Activity Completion</small>
                                <h3 class="mt-2">{{ $completionSummary['activities']['completed'] }}/{{ $completionSummary['activities']['total'] }}</h3>
                            </div>
                            <span class="badge {{ $completionSummary['activities']['completion_percentage'] === 100 ? 'bg-success' : 'bg-warning' }}">
                                {{ $completionSummary['activities']['completion_percentage'] }}%
                            </span>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar {{ $completionSummary['activities']['completion_percentage'] === 100 ? 'bg-success' : 'bg-warning' }}" 
                                 style="width: {{ $completionSummary['activities']['completion_percentage'] }}%"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-play-circle me-1"></i>Avg {{ $completionSummary['activities']['average_progress'] }}% progress
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted text-uppercase fw-bold">Payment Status</small>
                                <h3 class="mt-2">{{ $completionSummary['payments']['paid'] }}/{{ $completionSummary['payments']['total'] }}</h3>
                            </div>
                            <span class="badge {{ $completionSummary['payments']['completion_percentage'] === 100 ? 'bg-success' : 'bg-danger' }}">
                                {{ $completionSummary['payments']['completion_percentage'] }}%
                            </span>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar {{ $completionSummary['payments']['completion_percentage'] === 100 ? 'bg-success' : 'bg-danger' }}" 
                                 style="width: {{ $completionSummary['payments']['completion_percentage'] }}%"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            ₱{{ number_format($completionSummary['payments']['paid_amount'], 2) }} of ₱{{ number_format($completionSummary['payments']['total_amount'], 2) }}
                        </small>
                    </div>
                </div>
            </div>

            <div class="col-md-3">
                <div class="card border-0 shadow-sm">
                    <div class="card-body">
                        <div class="d-flex justify-content-between align-items-start">
                            <div>
                                <small class="text-muted text-uppercase fw-bold">Overall Status</small>
                                <h3 class="mt-2">{{ $completionSummary['overall']['overall_percentage'] }}%</h3>
                            </div>
                            <span class="badge {{ $completionSummary['overall']['badge_class'] }}">
                                {{ $completionSummary['overall']['status'] }}
                            </span>
                        </div>
                        <div class="progress mt-3" style="height: 8px;">
                            <div class="progress-bar {{ $completionSummary['overall']['badge_class'] }}" 
                                 style="width: {{ $completionSummary['overall']['overall_percentage'] }}%"></div>
                        </div>
                        <small class="text-muted mt-2 d-block">
                            <i class="bi bi-graph-up me-1"></i>Average completion
                        </small>
                    </div>
                </div>
            </div>
        </div>

        <!-- Detailed Courses Completion -->
        <div class="row mb-4">
            <div class="col-lg-8">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0"><i class="bi bi-book me-2"></i>Course Completion Details</h5>
                    </div>
                    <div class="card-body">
                        @forelse($student->enrollments()->with('course')->get() as $enrollment)
                            <div class="d-flex justify-content-between align-items-center mb-3 pb-3 border-bottom">
                                <div>
                                    <h6 class="mb-1">{{ $enrollment->course->title }}</h6>
                                    <small class="text-muted">{{ $enrollment->course->code }} • {{ $enrollment->semester }}</small>
                                </div>
                                <div class="text-end">
                                    <span class="badge {{ $enrollment->status === 'completed' ? 'bg-success' : ($enrollment->status === 'dropped' ? 'bg-danger' : 'bg-info') }}">
                                        {{ ucfirst($enrollment->status) }}
                                    </span>
                                </div>
                            </div>
                        @empty
                            <p class="text-muted text-center py-3">No course enrollments found</p>
                        @endforelse
                    </div>
                </div>
            </div>

            <div class="col-lg-4">
                <div class="card border-0 shadow-sm">
                    <div class="card-header bg-light border-0">
                        <h5 class="mb-0"><i class="bi bi-bar-chart me-2"></i>Status Breakdown</h5>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-success fw-bold">Completed</span>
                                <span class="badge bg-success">{{ $completionSummary['courses']['completed'] }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-success" style="width: {{ $completionSummary['courses']['total'] > 0 ? ($completionSummary['courses']['completed'] / $completionSummary['courses']['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-info fw-bold">In Progress</span>
                                <span class="badge bg-info">{{ $completionSummary['courses']['in_progress'] }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-info" style="width: {{ $completionSummary['courses']['total'] > 0 ? ($completionSummary['courses']['in_progress'] / $completionSummary['courses']['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between mb-2">
                                <span class="text-danger fw-bold">Dropped</span>
                                <span class="badge bg-danger">{{ $completionSummary['courses']['dropped'] }}</span>
                            </div>
                            <div class="progress" style="height: 6px;">
                                <div class="progress-bar bg-danger" style="width: {{ $completionSummary['courses']['total'] > 0 ? ($completionSummary['courses']['dropped'] / $completionSummary['courses']['total'] * 100) : 0 }}%"></div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Learning Activities Completion -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-list-check me-2"></i>Learning Activities Progress</h5>
            </div>
            <div class="card-body">
                @forelse($student->learningProgress()->with('material')->get() as $progress)
                    <div class="row align-items-center mb-3 pb-3 border-bottom">
                        <div class="col-md-6">
                            <h6 class="mb-1">{{ $progress->material->title }}</h6>
                            <small class="text-muted">{{ $progress->material->format }} • {{ $progress->time_spent_minutes }} mins</small>
                        </div>
                        <div class="col-md-6">
                            <div class="d-flex justify-content-between align-items-center">
                                <div class="progress flex-grow-1 me-3" style="height: 10px;">
                                    <div class="progress-bar {{ $progress->progress_percent === 100 ? 'bg-success' : 'bg-warning' }}" 
                                         style="width: {{ $progress->progress_percent }}%"></div>
                                </div>
                                <span class="badge {{ $progress->progress_percent === 100 ? 'bg-success' : 'bg-warning' }}">
                                    {{ $progress->progress_percent }}%
                                </span>
                            </div>
                            @if($progress->completed_at)
                                <small class="text-success d-block mt-2"><i class="bi bi-check-circle me-1"></i>Completed on {{ $progress->completed_at->format('M d, Y') }}</small>
                            @endif
                        </div>
                    </div>
                @empty
                    <p class="text-muted text-center py-3">No learning activities found</p>
                @endforelse
            </div>
        </div>
    @else
        <!-- Admin View - All Courses Completion Status -->
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-book me-2"></i>Course Completion Status Overview</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-muted small text-uppercase">
                        <th>Course</th>
                        <th>Code</th>
                        <th>Status</th>
                        <th>Students</th>
                        <th>Completion</th>
                        <th>Materials</th>
                    </thead>
                    <tbody>
                        @forelse($courses as $course)
                            <tr>
                                <td>
                                    <strong>{{ $course['title'] }}</strong>
                                </td>
                                <td><code>{{ $course['code'] }}</code></td>
                                <td>
                                    <span class="badge {{ $course['badge_class'] }}">{{ $course['status_label'] }}</span>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ $course['students_enrolled'] }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; width: 100px;">
                                            <div class="progress-bar bg-info" style="width: {{ $course['student_completion_percentage'] }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $course['student_completion_percentage'] }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <small class="text-muted">{{ $course['materials_completed'] }}/{{ $course['materials_count'] }}</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="6" class="text-center text-muted py-3">No courses found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>

        <!-- Learning Materials Status -->
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-light border-0">
                <h5 class="mb-0"><i class="bi bi-file-earmark me-2"></i>Learning Materials Completion Status</h5>
            </div>
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="text-muted small text-uppercase">
                        <th>Material</th>
                        <th>Format</th>
                        <th>Status</th>
                        <th>Progress</th>
                        <th>Students</th>
                    </thead>
                    <tbody>
                        @forelse($activities as $activity)
                            <tr>
                                <td>
                                    <strong>{{ $activity['title'] }}</strong>
                                </td>
                                <td>
                                    <span class="badge bg-light text-dark">{{ ucfirst($activity['format']) }}</span>
                                </td>
                                <td>
                                    <span class="badge {{ $activity['badge_class'] }}">{{ $activity['status_label'] }}</span>
                                </td>
                                <td>
                                    <div class="d-flex align-items-center gap-2">
                                        <div class="progress flex-grow-1" style="height: 6px; width: 100px;">
                                            <div class="progress-bar bg-warning" style="width: {{ $activity['average_progress'] }}%"></div>
                                        </div>
                                        <small class="text-muted">{{ $activity['average_progress'] }}%</small>
                                    </div>
                                </td>
                                <td>
                                    <span class="badge bg-secondary">{{ $activity['total_students'] }}</span>
                                    <small class="text-success d-block"><i class="bi bi-check-circle me-1"></i>{{ $activity['students_completed'] }} completed</small>
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="5" class="text-center text-muted py-3">No learning materials found</td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    @endif
</div>
@endsection
