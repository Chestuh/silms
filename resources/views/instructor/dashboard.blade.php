@extends('layouts.app')

@section('title', 'Instructor Dashboard')

@section('content')
<div class="row g-3 mb-4">
    <div class="col-12">
        <div class="card border-0 shadow-sm rounded-4 dashboard-hero">
            <div class="card-body">
                <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center justify-content-between gap-4">
                    <div>
                        <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Instructor Dashboard</span>
                        <h1 class="h3 fw-bold mb-2">Welcome back, {{ auth()->user()->name }}!</h1>
                        <p class="text-muted mb-3">Your teaching hub for course management, student progress, and learning materials.</p>
                        <div class="d-flex flex-wrap gap-2">
                            <span class="badge bg-success bg-opacity-15 text-white">Department: {{ $instructor->department ? 'Grade ' . $instructor->department : 'Faculty' }}</span>
                            <span class="badge bg-info bg-opacity-15 text-white">Today: {{ now()->format('F j, Y') }}</span>
                        </div>
                    </div>
                    <div class="text-start text-xl-end">
                        <div class="hero-highlights mt-1 p-4 rounded-4 bg-info bg-opacity-10 text-info">
                            <div class="small text-uppercase text-muted mb-1">Active courses</div>
                            <div class="h2 fw-bold mb-1">{{ $kpis['courses'] }}</div>
                            <div class="small text-muted">Courses you're currently teaching</div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Materials</div>
                    <div class="kpi-value">{{ $kpis['learning_materials'] }}</div>
                </div>
                <span class="badge bg-success bg-opacity-15 text-success p-2 rounded-circle"><i class="bi bi-folder2-open"></i></span>
            </div>
            <div class="text-muted small">Learning resources available.</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Enrollments</div>
                    <div class="kpi-value">{{ $kpis['enrolled_students'] }}</div>
                </div>
                <span class="badge bg-info bg-opacity-15 text-info p-2 rounded-circle"><i class="bi bi-people-fill"></i></span>
            </div>
            <div class="text-muted small">Student enrollments across your courses.</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Distinct students</div>
                    <div class="kpi-value">{{ $kpis['distinct_students'] }}</div>
                </div>
                <span class="badge bg-secondary bg-opacity-15 text-secondary p-2 rounded-circle"><i class="bi bi-person-badge"></i></span>
            </div>
            <div class="text-muted small">Unique learners across your classes.</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Avg students / course</div>
                    <div class="kpi-value">{{ $kpis['average_students_per_course'] }}</div>
                </div>
                <span class="badge bg-primary bg-opacity-15 text-primary p-2 rounded-circle"><i class="bi bi-bar-chart-line"></i></span>
            </div>
            <div class="text-muted small">Average enrolled students per active course.</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Completion rate</div>
                    <div class="kpi-value">{{ $kpis['completion_rate'] ?? 0 }}%</div>
                </div>
                <span class="badge bg-success bg-opacity-15 text-white p-2 rounded-pill">Live</span>
            </div>
            <div class="text-muted small">Progress toward course completion.</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Unread messages</div>
                    <div class="kpi-value">{{ $kpis['unread_messages'] }}</div>
                </div>
                <span class="badge bg-warning bg-opacity-15 text-warning p-2 rounded-circle"><i class="bi bi-chat-dots"></i></span>
            </div>
            <div class="text-muted small">New student inquiries waiting.</div>
        </div>
    </div>
    <div class="col-sm-6 col-xl-2">
        <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
            <div class="d-flex align-items-center justify-content-between mb-3">
                <div>
                    <div class="small text-uppercase text-muted kpi-label">Course load</div>
                    <div class="kpi-value">{{ $kpis['courses'] }}</div>
                </div>
                <span class="badge bg-primary bg-opacity-15 text-primary p-2 rounded-circle"><i class="bi bi-journal-text"></i></span>
            </div>
            <div class="text-muted small">Courses assigned to you this term.</div>
        </div>
    </div>
</div>

<div class="row g-4">
    <div class="col-lg-5">
        <div class="card h-100 border-0 shadow-sm dashboard-panel rounded-4">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-lightning me-2 text-warning"></i>Quick actions</h6>
            </div>
            <div class="card-body pt-2">
                <div class="list-group list-group-flush list-group-borderless">
                    <a href="{{ route('instructor.courses.index') }}" class="list-group-item list-group-item-action rounded-2 py-2 px-0">
                        <i class="bi bi-journal-richtext me-2 text-primary"></i>Manage courses
                    </a>
                    <a href="{{ route('instructor.materials.index') }}" class="list-group-item list-group-item-action rounded-2 py-2 px-0">
                        <i class="bi bi-folder-plus me-2 text-success"></i>Learning materials
                    </a>
                    <a href="{{ route('instructor.progress.index') }}" class="list-group-item list-group-item-action rounded-2 py-2 px-0">
                        <i class="bi bi-clipboard-data me-2 text-info"></i>View student progress
                    </a>
                    <a href="{{ route('instructor.messages.index') }}" class="list-group-item list-group-item-action rounded-2 py-2 px-0">
                        <i class="bi bi-chat-square-text me-2 text-warning"></i>Messages
                    </a>
                    <a href="{{ route('instructor.rubrics.index') }}" class="list-group-item list-group-item-action rounded-2 py-2 px-0">
                        <i class="bi bi-ui-checks me-2 text-secondary"></i>Rubrics & grading
                    </a>
                </div>
            </div>
        </div>
    </div>

    <div class="col-lg-7">
        <div class="card border-0 shadow-sm rounded-4 mb-4 dashboard-panel">
            <div class="card-header bg-transparent border-0 pt-3 pb-0">
                <h6 class="mb-0 fw-semibold"><i class="bi bi-person-gear me-2 text-primary"></i>Instructor profile</h6>
            </div>
            <div class="card-body pt-2">
                <div class="row small">
                    <div class="col-sm-4 py-2"><span class="text-muted">Department</span></div>
                    <div class="col-sm-8 py-2 fw-medium">{{ $instructor->department ? 'Grade ' . $instructor->department . ' Department' : '—' }}</div>
                    <div class="col-sm-4 py-2"><span class="text-muted">Email</span></div>
                    <div class="col-sm-8 py-2">{{ auth()->user()->email }}</div>
                    <div class="col-sm-4 py-2"><span class="text-muted">Courses</span></div>
                    <div class="col-sm-8 py-2">{{ $kpis['courses'] }}</div>
                    <div class="col-sm-4 py-2"><span class="text-muted">Materials</span></div>
                    <div class="col-sm-8 py-2">{{ $kpis['learning_materials'] }}</div>
                </div>
            </div>
        </div>
        {{-- <div class="card border-0 shadow-sm rounded-4 dashboard-panel">
            {{-- <div class="card-body">
                <h6 class="fw-semibold mb-2"><i class="bi bi-info-circle me-2 text-info"></i>What you can do</h6>
                <p class="small text-muted mb-0">
                    Manage your curriculum, review student progress, build learning materials, and respond to messages from one place.
                    The updated dashboard is designed to keep your most important metrics and actions front and center.
                </p>
            </div> --}}
        </div> 
    </div>
</div>
@endsection
