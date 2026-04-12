@extends('layouts.app')

@section('content')
<div class="container-fluid mt-4">
    <div class="row mb-4">
        <div class="col-md-12">
            <h1>📈 Progress Dashboard</h1>
            <p class="text-muted">Track your performance across all courses</p>
        </div>
    </div>

    <!-- Overall Metrics -->
    <div class="row mb-4">
        <div class="col-md-3">
            <div class="card border-left-primary shadow h-100">
                <div class="card-body">
                    <div class="text-primary font-weight-bold text-uppercase mb-1">Overall Completion</div>
                    <div class="h3 mb-0">{{ number_format($overallCompletion, 1) }}%</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-warning shadow h-100">
                <div class="card-body">
                    <div class="text-warning font-weight-bold text-uppercase mb-1">Enrolled Courses</div>
                    <div class="h3 mb-0">{{ $totalCourses }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-danger shadow h-100">
                <div class="card-body">
                    <div class="text-danger font-weight-bold text-uppercase mb-1">Need Attention</div>
                    <div class="h3 mb-0">{{ $atRiskCourses }}</div>
                </div>
            </div>
        </div>
        <div class="col-md-3">
            <div class="card border-left-success shadow h-100">
                <div class="card-body">
                    <div class="text-success font-weight-bold text-uppercase mb-1">On Track</div>
                    <div class="h3 mb-0">{{ $totalCourses - $atRiskCourses }}</div>
                </div>
            </div>
        </div>
    </div>

    <!-- Course Analytics -->
    <div class="row mb-4">
        <div class="col-md-12">
            <div class="card shadow-sm">
                <div class="card-header bg-primary text-white">
                    <h6 class="mb-0">📊 Course Performance</h6>
                </div>
                <div class="card-body">
                    @if($enrolledCourses->count() > 0)
                        <div class="table-responsive">
                            <table class="table table-hover">
                                <thead class="table-light">
                                    <tr>
                                        <th>Course</th>
                                        <th>Completion</th>
                                        <th>Current Grade</th>
                                        <th>Status</th>
                                        <th>Action</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($enrolledCourses as $course)
                                        @php
                                            $analytic = $courseAnalytics->firstWhere('course_id', $course->id);
                                        @endphp
                                        <tr>
                                            <td>
                                                <strong>{{ $course->title }}</strong>
                                            </td>
                                            <td>
                                                <div class="progress" style="height: 20px;">
                                                    <div class="progress-bar" role="progressbar" 
                                                         style="width: {{ $analytic->completion_rate ?? 0 }}%">
                                                        {{ number_format($analytic->completion_rate ?? 0, 1) }}%
                                                    </div>
                                                </div>
                                            </td>
                                            <td>
                                                @if($analytic && $analytic->current_grade)
                                                    <span class="badge badge-{{ $analytic->current_grade >= 75 ? 'success' : ($analytic->current_grade >= 60 ? 'warning' : 'danger') }}">
                                                        {{ number_format($analytic->current_grade, 2) }}
                                                    </span>
                                                @else
                                                    <span class="text-muted">No grades yet</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($analytic)
                                                    <span class="badge badge-{{ $analytic->at_risk_status === 'on_track' ? 'success' : ($analytic->at_risk_status === 'at_risk' ? 'warning' : 'danger') }}">
                                                        {{ ucfirst($analytic->at_risk_status) }}
                                                    </span>
                                                @else
                                                    <span class="badge badge-info">Just Started</span>
                                                @endif
                                            </td>
                                            <td>
                                                <a href="{{ route('student.progress.course-analytics', $course) }}" 
                                                   class="btn btn-sm btn-outline-primary">
                                                    View Details
                                                </a>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    @else
                        <p class="text-muted text-center py-4">No courses enrolled yet.</p>
                    @endif
                </div>
            </div>
        </div>
    </div>

    <!-- Courses Needing Attention -->
    @if($coursesNeedingAttention->count() > 0)
        <div class="row mb-4">
            <div class="col-md-12">
                <div class="alert alert-warning" role="alert">
                    <h5 class="alert-heading">⚠️ Courses Needing Attention</h5>
                    <p>These courses need your focus. Click to view personalized recommendations:</p>
                    @foreach($coursesNeedingAttention as $analytic)
                        <div class="mb-2">
                            <a href="{{ route('student.progress.course-analytics', $analytic->course) }}" class="btn btn-sm btn-warning">
                                {{ $analytic->course->title }} ({{ $analytic->at_risk_status }})
                            </a>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    @endif

    <!-- Weak Topics -->
    @if($weakTopics->count() > 0)
        <div class="row">
            <div class="col-md-12">
                <div class="card shadow-sm">
                    <div class="card-header">
                        <h6 class="mb-0">🎯 Most Frequent Weak Topics</h6>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            @foreach($weakTopics->take(5) as $topic => $count)
                                <div class="col-md-6 mb-3">
                                    <div class="card border-0 bg-light">
                                        <div class="card-body">
                                            <h6 class="card-title">{{ $topic }}</h6>
                                            <p class="text-muted small mb-0">Appears in {{ $count }} course(s)</p>
                                            <button class="btn btn-sm btn-outline-primary mt-2" 
                                                    onclick="generateJobAid('{{ $topic }}')">
                                                Get Help
                                            </button>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endif
</div>

<script>
function generateJobAid(topic) {
    alert('Job aid generation coming soon for: ' + topic);
    // window.location.href = '/student/job-aids?topic=' + encodeURIComponent(topic);
}
</script>
@endsection
