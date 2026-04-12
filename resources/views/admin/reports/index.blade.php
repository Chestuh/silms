@extends('layouts.app')

@section('title', 'School Reports and Analytics')

@section('content')
<script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
<h2 class="mb-4"><i class="bi bi-graph-up-arrow me-2"></i>School Reports and Analytics</h2>
<p class="text-muted mb-4">Portal-wide statistics and analytics.</p>

<!-- Date Range Filter -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <form method="GET" class="row g-3 align-items-end">
            <div class="col-md-3">
                <label for="start_date" class="form-label">Start Date</label>
                <input type="date" class="form-control" id="start_date" name="start_date" 
                    value="{{ $startDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-3">
                <label for="end_date" class="form-label">End Date</label>
                <input type="date" class="form-control" id="end_date" name="end_date"
                    value="{{ $endDate->format('Y-m-d') }}">
            </div>
            <div class="col-md-6">
                <button type="submit" class="btn btn-primary">
                    <i class="bi bi-funnel me-2"></i>Filter
                </button>
                <a href="{{ route('admin.reports.index') }}" class="btn btn-secondary">
                    Reset
                </a>
                <a href="{{ route('admin.reports.export', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d'), 'format' => 'csv']) }}" class="btn btn-success">
                    <i class="bi bi-download me-2"></i>Export CSV
                </a>
            </div>
        </form>
    </div>
</div>

<!-- New Live Report reference section -->
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <div class="d-flex justify-content-between align-items-center mb-3">
            <div>
                <h3 class="mb-1">Live Report</h3>
                <small class="text-muted">Portal-wide metrics in one place</small>
            </div>
            <div class="d-flex align-items-center gap-2">
                <span class="badge bg-light text-dark">{{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}</span>
                <button class="btn btn-outline-secondary btn-sm" type="button"><i class="bi bi-list"></i></button>
            </div>
        </div>

        <div class="row g-3 mb-3">
            <div class="col-md-8">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent"><strong>App performance</strong></div>
                    <div class="card-body" style="height: 240px;">
                        <canvas id="appPerformanceChart"></canvas>
                    </div>
                </div>
            </div>
            <div class="col-md-4">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-header bg-transparent"><strong>Performance Tracker</strong></div>
                    <div class="card-body">
                        <div class="mb-3">
                            <div class="d-flex justify-content-between"><small>Total Users</small><strong>{{ $stats['users_total'] }}</strong></div>
                            <div class="progress" style="height: 8px"><div class="progress-bar bg-primary" role="progressbar" style="width: {{ min(100, max(5, round($stats['users_total'] / 20))) }}%"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between"><small>Active Students</small><strong>{{ $stats['students_active'] }}</strong></div>
                            <div class="progress" style="height: 8px"><div class="progress-bar bg-success" role="progressbar" style="width: {{ min(100, max(5, round($stats['students_active'] / 10))) }}%"></div></div>
                        </div>
                        <div class="mb-3">
                            <div class="d-flex justify-content-between"><small>Completion Rate</small><strong>{{ $stats['completion_rate'] }}%</strong></div>
                            <div class="progress" style="height: 8px"><div class="progress-bar bg-warning" role="progressbar" style="width: {{ $stats['completion_rate'] }}%"></div></div>
                        </div>
                        <div>
                            <div class="d-flex justify-content-between"><small>Pending Fees</small><strong>₱{{ number_format($stats['fees_pending_amount'],0) }}</strong></div>
                            <div class="progress" style="height: 8px"><div class="progress-bar bg-danger" role="progressbar" style="width: {{ min(100, max(5, round($stats['fees_pending_amount'] / max(1, $stats['fees_paid_amount'] + $stats['fees_pending_amount']) * 100))) }}%"></div></div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartUsers"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-primary">{{ $stats['users_total'] }}</div>
                    <div class="small text-muted">Total Users</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartStudents"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-success">{{ $stats['students_total'] }}</div>
                    <div class="small text-muted">Students</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartActiveStudents"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-success">{{ $stats['students_active'] }}</div>
                    <div class="small text-muted">Active Students</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.instructors.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartInstructors"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['instructors_total'] }}</div>
                    <div class="small text-muted">Instructors</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.courses.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartCourses"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-info">{{ $stats['courses_total'] }}</div>
                    <div class="small text-muted">Courses</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.enrollments.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartEnrollments"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['enrollments_active'] }}</div>
                    <div class="small text-muted">Active Enrollments</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.materials.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartMaterials"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['materials_total'] }}</div>
                    <div class="small text-muted">Learning Materials</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.materials.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartMaterialsStatus"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['materials_archived'] }}</div>
                    <div class="small text-muted">Archived</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.students.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartProgress"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['progress_completed'] }}/{{ $stats['progress_records'] }}</div>
                    <div class="small text-muted">Progress Completed</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartCompletion"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-success">{{ $stats['completion_rate'] }}%</div>
                    <div class="small text-muted">Completion Rate</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('cashier.fees.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 border-warning hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartFees"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['fees_pending_count'] }}</div>
                    <div class="small text-muted">Pending Fees</div>
                    <div class="small">₱{{ number_format($stats['fees_pending_amount'], 0) }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.credentials.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartCredentials"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['credentials_pending'] }}</div>
                    <div class="small text-muted">Credentials Pending</div>
                    <div class="small text-muted">{{ $stats['credentials_ready'] }} ready</div>
                </div>
            </div>
        </a>
    </div>
</div>

<!-- Additional Stat Cards: Pre-Registrations, Academic, & Messaging -->
<div class="row g-3 mb-4">
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.pre-registrations.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartPreReg"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-warning">{{ $stats['pre_registrations_pending'] }}</div>
                    <div class="small text-muted">Pre-Reg Pending</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.pre-registrations.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartPreRegStatus"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-success">{{ $stats['pre_registrations_approved'] }}</div>
                    <div class="small text-muted">Pre-Reg Approved</div>
                    <div class="small">Rejected: {{ $stats['pre_registrations_rejected'] }}</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartHonors"></canvas>
                    </div>
                    <div class="fw-bold fs-5 text-info">{{ $stats['honors_count'] }}</div>
                    <div class="small text-muted">Honors Awarded</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartGrade"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['avg_final_grade'] }}</div>
                    <div class="small text-muted">Avg Final Grade</div>
                    <div class="small">{{ $stats['grades_total'] }} grades</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartRatings"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['avg_material_rating'] }}★</div>
                    <div class="small text-muted">Avg Material Rating</div>
                    <div class="small">{{ $stats['material_ratings_count'] }} ratings</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-4 col-lg-2">
        <a href="{{ route('admin.reports.index') }}" class="text-decoration-none text-dark">
            <div class="card border-0 shadow-sm h-100 hover-shadow">
                <div class="card-body text-center py-2">
                    <div style="position: relative; height: 80px; margin-bottom: 8px;">
                        <canvas id="chartDisciplinary"></canvas>
                    </div>
                    <div class="fw-bold fs-5">{{ $stats['disciplinary_records'] }}</div>
                    <div class="small text-muted">Disciplinary Records</div>
                    <div class="small">{{ $stats['disciplinary_recent'] }} recent</div>
                </div>
            </div>
        </a>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent"><strong>Top Courses by Enrollment</strong></div>
    <div class="card-body">
        <div style="position: relative; height: 300px;">
            <canvas id="enrollmentByCoursesChart"></canvas>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><strong>Fee Collection Status</strong></div>
            <div class="card-body">
                <div style="position: relative; height: 250px;">
                    <canvas id="feeStatusChart"></canvas>
                </div>
                <div class="mt-3">
                    @foreach($stats['fees_by_status'] as $fee)
                    <div class="d-flex justify-content-between mb-2">
                        <span class="text-uppercase small fw-bold">{{ $fee['status'] }}</span>
                        <span>₱{{ number_format($fee['amount'], 2) }} ({{ $fee['count'] }} items)</span>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><strong>Student Status Distribution</strong></div>
            <div class="card-body">
                <div style="position: relative; height: 250px;">
                    <canvas id="studentStatusChart"></canvas>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><strong>Learning Materials by Type</strong></div>
            <div class="card-body">
                <div style="position: relative; height: 250px;">
                    <canvas id="materialTypeChart"></canvas>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-header bg-transparent"><strong>Pre-Registration Status Overview</strong></div>
            <div class="card-body">
                <div class="row text-center">
                    <div class="col-4">
                        <h4 class="text-warning">{{ $stats['pre_registrations_pending'] }}</h4>
                        <small class="text-muted">Pending</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-success">{{ $stats['pre_registrations_approved'] }}</h4>
                        <small class="text-muted">Approved</small>
                    </div>
                    <div class="col-4">
                        <h4 class="text-danger">{{ $stats['pre_registrations_rejected'] }}</h4>
                        <small class="text-muted">Rejected</small>
                    </div>
                </div>
                <hr>
                <div class="mt-3">
                    <div style="position: relative; height: 200px;">
                        <canvas id="preRegStatusChart"></canvas>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent"><strong>Key Performance Indicators</strong></div>
    <div class="card-body">
        <div class="row text-center">
            <div class="col-md-3">
                <h5>Enrollment Growth</h5>
                <h2 class="text-primary">{{ $stats['enrollments_active'] }}</h2>
                <small class="text-muted">Active Enrollments</small>
            </div>
            <div class="col-md-3">
                <h5>Academic Performance</h5>
                <h2 class="text-info">{{ $stats['avg_final_grade'] }}/4.0</h2>
                <small class="text-muted">Average Grade</small>
            </div>
            <div class="col-md-3">
                <h5>Fee Collection</h5>
                <h2 class="text-success">₱{{ number_format($stats['fees_paid_amount'], 0) }}</h2>
                <small class="text-muted">Total Collected ({{ $stats['fees_paid_count'] }} paid)</small>
            </div>
            <div class="col-md-3">
                <h5>Learning Progress</h5>
                <h2 class="text-center">
                    <div style="position: relative; width: 100px; height: 100px; margin: 0 auto;">
                        <canvas id="kpiProgressChart"></canvas>
                    </div>
                </h2>
                <small class="text-muted">{{ $stats['completion_rate'] }}% Complete</small>
            </div>
        </div>
    </div>
</div>

<!-- <div class="card border-0 shadow-sm">
    <div class="card-header bg-transparent"><strong>Analytics Summary</strong></div>
    <div class="card-body">
        <p class="mb-0 text-muted small">Use this page for school-wide reports and analytics. You can filter results by date range using the filters above. Data includes:</p>
        <ul class="text-muted small mt-2 mb-0">
            <li>User and enrollment management metrics</li>
            <li>Academic performance and grade analysis</li>
            <li>Financial tracking (fees and payments)</li>
            <li>Pre-registration and admission statistics</li>
            <li>Learning material and student progress</li>
            <li>Disciplinary and honors records</li>
        </ul>
    </div>
</div> -->

<p class="small text-muted mt-3 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const chartConfig = {
        responsive: true,
        maintainAspectRatio: false,
        plugins: {
            legend: { display: false }
        }
    };

    // Total Users - Doughnut Chart
    new Chart(document.getElementById('chartUsers'), {
        type: 'doughnut',
        data: {
            labels: ['Users'],
            datasets: [{
                data: [{{ $stats['users_total'] }}],
                backgroundColor: ['#0d6efd'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Students - Doughnut Chart
    new Chart(document.getElementById('chartStudents'), {
        type: 'doughnut',
        data: {
            labels: ['Total', 'Others'],
            datasets: [{
                data: [{{ $stats['students_total'] }}, Math.max(1, {{ $stats['users_total'] }} - {{ $stats['students_total'] }})],
                backgroundColor: ['#198754', '#e9ecef'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Active Students - Progress Doughnut
    new Chart(document.getElementById('chartActiveStudents'), {
        type: 'doughnut',
        data: {
            labels: ['Active', 'Inactive'],
            datasets: [{
                data: [{{ $stats['students_active'] }}, Math.max(0, {{ $stats['students_total'] }} - {{ $stats['students_active'] }})],
                backgroundColor: ['#198754', '#dee2e6'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Instructors - Bar Chart
    new Chart(document.getElementById('chartInstructors'), {
        type: 'bar',
        data: {
            labels: ['Instructors'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['instructors_total'] }}],
                backgroundColor: '#6c757d',
                borderColor: '#5a6268',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['instructors_total'] }}, 10) }
            }
        }
    });

    // Courses - Bar Chart
    new Chart(document.getElementById('chartCourses'), {
        type: 'bar',
        data: {
            labels: ['Courses'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['courses_total'] }}],
                backgroundColor: '#0dcaf0',
                borderColor: '#0aa2c0',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['courses_total'] }}, 10) }
            }
        }
    });

    // Active Enrollments - Bar Chart
    new Chart(document.getElementById('chartEnrollments'), {
        type: 'bar',
        data: {
            labels: ['Enrollments'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['enrollments_active'] }}],
                backgroundColor: '#6c757d',
                borderColor: '#5a6268',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['enrollments_active'] }}, 10) }
            }
        }
    });

    // App Performance - Line Chart (new)
    new Chart(document.getElementById('appPerformanceChart'), {
        type: 'line',
        data: {
            labels: ['Mon', 'Tue', 'Wed', 'Thu', 'Fri', 'Sat', 'Sun'],
            datasets: [{
                label: 'User Activity',
                data: [
                    {{ max(1, round($stats['users_total'] * 0.1)) }},
                    {{ max(1, round($stats['users_total'] * 0.18)) }},
                    {{ max(1, round($stats['users_total'] * 0.16)) }},
                    {{ max(1, round($stats['users_total'] * 0.22)) }},
                    {{ max(1, round($stats['users_total'] * 0.2)) }},
                    {{ max(1, round($stats['users_total'] * 0.23)) }},
                    {{ max(1, round($stats['users_total'] * 0.19)) }}
                ],
                backgroundColor: 'rgba(13, 110, 253, 0.1)',
                borderColor: '#0d6efd',
                fill: true,
                tension: 0.3,
                pointRadius: 3
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Learning Materials - Doughnut
    new Chart(document.getElementById('chartMaterials'), {
        type: 'doughnut',
        data: {
            labels: ['Materials'],
            datasets: [{
                data: [{{ $stats['materials_total'] }}],
                backgroundColor: ['#6c757d'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Materials Status - Pie (Active vs Archived)
    new Chart(document.getElementById('chartMaterialsStatus'), {
        type: 'pie',
        data: {
            labels: ['Active', 'Archived'],
            datasets: [{
                data: [Math.max(0, {{ $stats['materials_total'] }} - {{ $stats['materials_archived'] }}), {{ $stats['materials_archived'] }}],
                backgroundColor: ['#198754', '#ffc107'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Progress Completed - Horizontal Bar
    new Chart(document.getElementById('chartProgress'), {
        type: 'bar',
        data: {
            labels: ['Progress'],
            datasets: [{
                label: 'Completed',
                data: [{{ $stats['progress_completed'] }}],
                backgroundColor: '#198754',
                borderColor: '#146c43',
                borderWidth: 1
            }, {
                label: 'Pending',
                data: [{{ $stats['progress_records'] - $stats['progress_completed'] }}],
                backgroundColor: '#dee2e6',
                borderColor: '#adb5bd',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            indexAxis: 'y',
            scales: {
                x: { stacked: true, beginAtZero: true }
            }
        }
    });

    // Completion Rate - Doughnut Gauge
    new Chart(document.getElementById('chartCompletion'), {
        type: 'doughnut',
        data: {
            labels: ['Completed', 'Remaining'],
            datasets: [{
                data: [{{ $stats['completion_rate'] }}, {{ 100 - $stats['completion_rate'] }}],
                backgroundColor: ['#198754', '#e9ecef'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Pending Fees - Bar Chart
    new Chart(document.getElementById('chartFees'), {
        type: 'bar',
        data: {
            labels: ['Pending'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['fees_pending_count'] }}],
                backgroundColor: '#ffc107',
                borderColor: '#e0a800',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['fees_pending_count'] }}, 5) }
            }
        }
    });

    // Credentials - Pie (Pending vs Ready)
    new Chart(document.getElementById('chartCredentials'), {
        type: 'pie',
        data: {
            labels: ['Pending', 'Ready'],
            datasets: [{
                data: [{{ $stats['credentials_pending'] }}, {{ $stats['credentials_ready'] }}],
                backgroundColor: ['#dc3545', '#28a745'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // NEW CHARTS

    // Pre-Registrations Pending - Bar
    new Chart(document.getElementById('chartPreReg'), {
        type: 'bar',
        data: {
            labels: ['Pending'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['pre_registrations_pending'] }}],
                backgroundColor: '#ffc107',
                borderColor: '#e0a800',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['pre_registrations_pending'] }}, 5) }
            }
        }
    });

    // Pre-Registration Status - Doughnut
    new Chart(document.getElementById('chartPreRegStatus'), {
        type: 'doughnut',
        data: {
            labels: ['Approved', 'Rejected'],
            datasets: [{
                data: [{{ $stats['pre_registrations_approved'] }}, {{ $stats['pre_registrations_rejected'] }}],
                backgroundColor: ['#198754', '#dc3545'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Honors - Bar
    new Chart(document.getElementById('chartHonors'), {
        type: 'bar',
        data: {
            labels: ['Honors'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['honors_count'] }}],
                backgroundColor: '#0dcaf0',
                borderColor: '#0aa2c0',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['honors_count'] }}, 5) }
            }
        }
    });

    // Average Grade - Gauge
    new Chart(document.getElementById('chartGrade'), {
        type: 'doughnut',
        data: {
            labels: ['Achieved', 'Remaining'],
            datasets: [{
                data: [{{ $stats['avg_final_grade'] }} * 25, (4 - {{ $stats['avg_final_grade'] }}) * 25],
                backgroundColor: ['#0d6efd', '#e9ecef'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Material Ratings - Gauge
    new Chart(document.getElementById('chartRatings'), {
        type: 'doughnut',
        data: {
            labels: ['Rated', 'Stars Remaining'],
            datasets: [{
                data: [{{ $stats['avg_material_rating'] }} * 20, (5 - {{ $stats['avg_material_rating'] }}) * 20],
                backgroundColor: ['#ffc107', '#e9ecef'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: chartConfig
    });

    // Disciplinary Records - Bar
    new Chart(document.getElementById('chartDisciplinary'), {
        type: 'bar',
        data: {
            labels: ['Total Records'],
            datasets: [{
                label: 'Count',
                data: [{{ $stats['disciplinary_records'] }}],
                backgroundColor: '#dc3545',
                borderColor: '#bb2d3b',
                borderWidth: 1
            }]
        },
        options: {
            ...chartConfig,
            scales: {
                y: { beginAtZero: true, max: Math.max({{ $stats['disciplinary_records'] }}, 5) }
            }
        }
    });

    // Enrollment by Courses - Bar Chart
    new Chart(document.getElementById('enrollmentByCoursesChart'), {
        type: 'bar',
        data: {
            labels: [
                @forelse($stats['enrollments_by_course'] as $course)
                    '{{ $course['name'] }}',
                @empty
                    'No Courses'
                @endforelse
            ],
            datasets: [{
                label: 'Enrollments',
                data: [
                    @forelse($stats['enrollments_by_course'] as $course)
                        {{ $course['count'] }},
                    @empty
                        0
                    @endforelse
                ],
                backgroundColor: '#0dcaf0',
                borderColor: '#0aa2c0',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: true }
            }
        }
    });

    // Fee Status - Pie
    const feeStatuses = [
        @forelse($stats['fees_by_status'] as $fee)
            '{{ $fee['status'] }}',
        @empty
            'No Data'
        @endforelse
    ];
    const feeAmounts = [
        @forelse($stats['fees_by_status'] as $fee)
            {{ $fee['amount'] }},
        @empty
            0
        @endforelse
    ];
    const feeColors = {
        'pending': '#ffc107',
        'paid': '#198754',
        'overdue': '#dc3545',
        'cancelled': '#6c757d'
    };
    
    new Chart(document.getElementById('feeStatusChart'), {
        type: 'pie',
        data: {
            labels: feeStatuses,
            datasets: [{
                data: feeAmounts,
                backgroundColor: feeStatuses.map(status => feeColors[status] || '#999'),
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'bottom' }
            }
        }
    });

    // Student Status - Bar
    const studentStatuses = [
        @forelse($stats['students_by_status'] as $status => $count)
            '{{ $status }}',
        @empty
            'No Data'
        @endforelse
    ];
    const studentCounts = [
        @forelse($stats['students_by_status'] as $status => $count)
            {{ $count }},
        @empty
            0
        @endforelse
    ];
    
    new Chart(document.getElementById('studentStatusChart'), {
        type: 'bar',
        data: {
            labels: studentStatuses,
            datasets: [{
                label: 'Count',
                data: studentCounts,
                backgroundColor: ['#198754', '#dc3545', '#ffc107'],
                borderColor: '#999',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // Material Type - Doughnut
    const materialTypes = [
        @forelse($stats['materials_by_type'] as $format => $count)
            '{{ $format }}',
        @empty
            'No Data'
        @endforelse
    ];
    const materialCounts = [
        @forelse($stats['materials_by_type'] as $format => $count)
            {{ $count }},
        @empty
            0
        @endforelse
    ];
    
    new Chart(document.getElementById('materialTypeChart'), {
        type: 'doughnut',
        data: {
            labels: materialTypes,
            datasets: [{
                data: materialCounts,
                backgroundColor: [
                    '#0d6efd',
                    '#198754',
                    '#0dcaf0',
                    '#ffc107',
                    '#dc3545',
                    '#6c757d'
                ],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: true, position: 'right' }
            }
        }
    });

    // Pre-Registration Status Overview - Bar
    new Chart(document.getElementById('preRegStatusChart'), {
        type: 'bar',
        data: {
            labels: ['Pending', 'Approved', 'Rejected'],
            datasets: [{
                label: 'Count',
                data: [
                    {{ $stats['pre_registrations_pending'] }},
                    {{ $stats['pre_registrations_approved'] }},
                    {{ $stats['pre_registrations_rejected'] }}
                ],
                backgroundColor: ['#ffc107', '#198754', '#dc3545'],
                borderColor: '#999',
                borderWidth: 1
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            scales: {
                y: { beginAtZero: true }
            },
            plugins: {
                legend: { display: false }
            }
        }
    });

    // KPI Progress Chart - Doughnut
    new Chart(document.getElementById('kpiProgressChart'), {
        type: 'doughnut',
        data: {
            labels: ['Complete', 'Remaining'],
            datasets: [{
                data: [{{ $stats['completion_rate'] }}, {{ 100 - $stats['completion_rate'] }}],
                backgroundColor: ['#198754', '#e9ecef'],
                borderColor: '#fff',
                borderWidth: 2
            }]
        },
        options: {
            responsive: true,
            maintainAspectRatio: false,
            plugins: {
                legend: { display: false }
            }
        }
    });
});
</script>
@endsection
