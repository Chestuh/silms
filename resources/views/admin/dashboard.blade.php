@extends('layouts.app')

@section('title', 'Admin KPI Dashboard')

@section('content')
<div class="admin-dashboard">
    <div class="row g-3 mb-4">
        <div class="col-12">
            <div class="card border-0 shadow-sm rounded-4 dashboard-hero">
                <div class="card-body">
                    <div class="d-flex flex-column flex-xl-row align-items-start align-items-xl-center justify-content-between gap-4">
                        <div>
                            <span class="badge bg-primary bg-opacity-10 text-primary mb-2">Admin Dashboard</span>
                            <h1 class="h3 fw-bold mb-2">Welcome back, Principal!</h1>
                            <p class="text-muted mb-3">Your central dashboard for enrollment trends, material usage, grade distribution, and pending approvals.</p>
                            <div class="d-flex flex-wrap gap-2">
                                <span class="badge bg-success bg-opacity-15 text-white">Online</span>
                                <span id="live-date-badge" class="badge bg-info bg-opacity-15 text-white">Today: </span>
                            </div>
                        </div>
                        <div class="text-start text-xl-end">
                            <div class="hero-highlights mt-1 p-4 rounded-4 bg-info bg-opacity-10 text-info">
                                <div class="small text-uppercase text-muted mb-1">Active Enrollments</div>
                                <div class="h2 fw-bold mb-1">{{ $kpis['active_enrollments'] }}</div>
                                <div class="small text-muted">Students currently enrolled</div>
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
                        <div class="small text-uppercase text-muted kpi-label">Total Students</div>
                        <div class="kpi-value">{{ $kpis['students'] }}</div>
                    </div>
                    <span class="badge bg-primary bg-opacity-15 text-primary p-2 rounded-circle"><i class="bi bi-people-fill"></i></span>
                </div>
                <div class="text-muted small">Active students in the portal.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Active Courses</div>
                        <div class="kpi-value">{{ $kpis['active_courses'] }}</div>
                    </div>
                    <span class="badge bg-success bg-opacity-15 text-success p-2 rounded-circle"><i class="bi bi-book-half"></i></span>
                </div>
                <div class="text-muted small">Courses currently available.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Materials Uploaded</div>
                        <div class="kpi-value">{{ $kpis['materials_uploaded'] }}</div>
                    </div>
                    <span class="badge bg-warning bg-opacity-15 text-warning p-2 rounded-circle"><i class="bi bi-folder2-open"></i></span>
                </div>
                <div class="text-muted small">Learning items ready for students.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Pass Rate</div>
                        <div id="kpi-pass-rate-value" class="kpi-value">{{ $kpis['pass_rate'] }}%</div>
                    </div>
                    <span id="kpi-pass-rate-badge" class="badge bg-success bg-opacity-10 text-success p-2 rounded-pill">Live</span>
                </div>
                <div class="text-muted small">Students passing across graded courses.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Completion Rate</div>
                        <div id="kpi-completion-rate-value" class="kpi-value">{{ $kpis['completion_rate'] }}%</div>
                    </div>
                    <span id="kpi-completion-rate-badge" class="badge bg-info bg-opacity-10 text-info p-2 rounded-pill">Live</span>
                </div>
                <div class="text-muted small">Enrollment completions for current records.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Avg Engagement</div>
                        <div id="kpi-avg-engagement-value" class="kpi-value">{{ round($kpis['avg_engagement_time']) }}<span class="fs-6">m</span></div>
                    </div>
                    <span id="kpi-engagement-badge" class="badge bg-info bg-opacity-15 text-white p-2 rounded-circle"><i class="bi bi-clock-fill"></i></span>
                </div>
                <div class="text-muted small">Average time spent per material.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Avg Grade</div>
                        <div class="kpi-value">{{ $kpis['avg_grade'] }}%</div>
                    </div>
                    <span class="badge bg-secondary bg-opacity-15 text-secondary p-2 rounded-circle"><i class="bi bi-bar-chart-fill"></i></span>
                </div>
                <div class="text-muted small">Average final grade across courses.</div>
            </div>
        </div>
        <div class="col-sm-6 col-xl-2">
            <div class="card border-0 shadow-sm rounded-4 kpi-card h-100 p-3">
                <div class="d-flex align-items-center justify-content-between mb-3">
                    <div>
                        <div class="small text-uppercase text-muted kpi-label">Pending Approvals</div>
                        <div class="kpi-value">{{ $kpis['pending_approvals'] }}</div>
                    </div>
                    <span class="badge bg-danger bg-opacity-15 text-danger p-2 rounded-circle"><i class="bi bi-exclamation-circle-fill"></i></span>
                </div>
                <div class="text-muted small">Applications and requests awaiting review.</div>
            </div>
        </div>
    </div>
    
    <div class="row g-3 mb-4">
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">Student Enrollment Trend</h6>
                            <p class="text-muted small mb-0">Monthly enrollment growth.</p>
                        </div>
                        <span id="enrollmentTrendBadge" class="badge bg-success bg-opacity-10 text-success">Live</span>
                    </div>
                    <div style="height: 260px;"><canvas id="enrollmentTrendChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">Learning Materials Usage</h6>
                            <p class="text-muted small mb-0">Engagement by subject area.</p>
                        </div>
                        <span id="materialsUsageBadge" class="badge bg-success bg-opacity-10 text-success">Live</span>
                    </div>
                    <div style="height: 260px;"><canvas id="materialsUsageChart"></canvas></div>
                </div>
            </div>
        </div>
        <div class="col-xl-4">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">Grade Distribution</h6>
                            <p class="text-muted small mb-0">Performance segments across learners.</p>
                        </div>
                        <span id="gradeDistributionBadge" class="badge bg-warning bg-opacity-10 text-warning">Live</span>
                    </div>
                    <div style="height: 260px;"><canvas id="gradeDistributionChart"></canvas></div>
                </div>
            </div>
        </div>
    </div>
    
    <div class="row g-3 mb-4">
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">Recent Activity</h6>
                            <p class="text-muted small mb-0">Latest portal actions from students and staff.</p>
                        </div>
                        <span class="badge bg-secondary bg-opacity-15 text-white">Latest</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex gap-3 align-items-start">
                            <span class="badge bg-success rounded-pill mt-1"><i class="bi bi-cloud-arrow-up-fill"></i></span>
                            <div>
                                <div class="fw-semibold">{{ $kpis['materials_uploaded'] }} materials uploaded</div>
                                <div class="small text-muted">New learning resources added this week.</div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex gap-3 align-items-start">
                            <span class="badge bg-primary rounded-pill mt-1"><i class="bi bi-person-plus-fill"></i></span>
                            <div>
                                <div class="fw-semibold">{{ $recentApplications->count() }} new student applications</div>
                                <div class="small text-muted">Pending review and approval.</div>
                            </div>
                            <div class="ms-auto align-self-start">
                                <a href="{{ route('admin.pre-registrations.index', ['status' => 'pending']) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-eye"></i> View Applications
                                </a>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex gap-3 align-items-start">
                            <span class="badge bg-warning rounded-pill mt-1"><i class="bi bi-exclamation-triangle-fill"></i></span>
                            <div>
                                <div class="fw-semibold">{{ $kpis['pending_fees_count'] }} pending fee transactions</div>
                                <div class="small text-muted">Outstanding student balances.</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
        <div class="col-xl-6">
            <div class="card border-0 shadow-sm rounded-4 h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-start mb-3">
                        <div>
                            <h6 class="fw-semibold mb-1">Alerts & Notifications</h6>
                            <p class="text-muted small mb-0">Action items that need your attention.</p>
                        </div>
                        <span class="badge bg-danger bg-opacity-10 text-danger">Urgent</span>
                    </div>
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item px-0 py-3 d-flex gap-3 align-items-start">
                            <span class="badge bg-danger rounded-pill mt-1"><i class="bi bi-envelope-fill"></i></span>
                            <div>
                                <div class="fw-semibold">New enrollment requests pending</div>
                                <div class="small text-muted">Review student pre-registrations now.</div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex gap-3 align-items-start">
                            <span class="badge bg-warning rounded-pill mt-1"><i class="bi bi-journal-bookmark-fill"></i></span>
                            <div>
                                <div class="fw-semibold">Materials need approval</div>
                                <div class="small text-muted">Check flagged uploads and resources.</div>
                            </div>
                        </li>
                        <li class="list-group-item px-0 py-3 d-flex gap-3 align-items-start">
                            <span class="badge bg-info rounded-pill mt-1"><i class="bi bi-gear-fill"></i></span>
                            <div>
                                <div class="fw-semibold">System maintenance scheduled</div>
                                <div class="small text-muted">Planned window tonight at 10:00 PM.</div>
                            </div>
                        </li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    <div class="modal fade" id="dashboardRejectModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Reject Application</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <form id="dashboardRejectForm" method="POST" action="">
                    @csrf
                    <div class="modal-body">
                        <p class="small text-muted" id="dashboardRejectApplicant"></p>
                        <div class="mb-3">
                            <label class="form-label">Rejection Reason</label>
                            <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this pre-registration is being rejected..."></textarea>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                        <button type="submit" class="btn btn-danger">Reject</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.js"></script>
<script>
    // Live date badge updater
    function updateLiveDateBadge() {
        const badge = document.getElementById('live-date-badge');
        if (!badge) return;
        const now = new Date();
        // Format: Month Day, Year (e.g., April 13, 2026)
        const options = { year: 'numeric', month: 'long', day: 'numeric' };
        badge.textContent = 'Today: ' + now.toLocaleDateString(undefined, options);
    }
    document.addEventListener('DOMContentLoaded', function () {
        updateLiveDateBadge();
        // Update every second for real-time accuracy
        setInterval(updateLiveDateBadge, 1000);
    });
    document.addEventListener('DOMContentLoaded', function () {
        const rejectButtons = document.querySelectorAll('[data-bs-target="#dashboardRejectModal"]');
        const rejectForm = document.getElementById('dashboardRejectForm');
        const applicantText = document.getElementById('dashboardRejectApplicant');
        
        rejectButtons.forEach(button => {
            button.addEventListener('click', function () {
                const id = this.getAttribute('data-id');
                const name = this.getAttribute('data-name');
                rejectForm.action = `/admin/pre-registrations/${id}/reject`;
                applicantText.textContent = `Reject application from ${name}?`;
            });
        });
        
        const trendBadge = document.getElementById('enrollmentTrendBadge');
        const enrollmentTrendCtx = document.getElementById('enrollmentTrendChart');
        let enrollmentTrendChart;
        
        const createEnrollmentTrendChart = (labels, data) => {
            enrollmentTrendChart = new Chart(enrollmentTrendCtx, {
                type: 'line',
                data: {
                    labels,
                    datasets: [{
                        label: 'New Enrollments',
                        data,
                        backgroundColor: 'rgba(13,110,253,0.15)',
                        borderColor: '#0d6efd',
                        borderWidth: 2,
                        fill: true,
                        tension: 0.35,
                        pointRadius: 3,
                        pointBackgroundColor: '#0d6efd'
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: { stepSize: 20 }
                        }
                    }
                }
            });
        };
        
        const refreshEnrollmentTrend = async () => {
            try {
                const response = await fetch('{{ route('admin.dashboard.enrollment-data') }}');
                if (!response.ok) throw new Error('Failed to fetch enrollment data');
                const payload = await response.json();
                
                if (!enrollmentTrendChart) {
                    createEnrollmentTrendChart(payload.labels, payload.data);
                } else {
                    enrollmentTrendChart.data.labels = payload.labels;
                    enrollmentTrendChart.data.datasets[0].data = payload.data;
                    enrollmentTrendChart.update();
                }
                
                if (trendBadge) {
                    trendBadge.textContent = `Live • ${new Date(payload.lastUpdated).toLocaleTimeString()}`;
                }
            } catch (error) {
                console.error(error);
                if (trendBadge) {
                    trendBadge.textContent = 'Live • update failed';
                }
            }
        };
        
        if (enrollmentTrendCtx) {
            refreshEnrollmentTrend();
            setInterval(refreshEnrollmentTrend, 60000);
        }
        
        const passRateValue = document.getElementById('kpi-pass-rate-value');
        const completionRateValue = document.getElementById('kpi-completion-rate-value');
        const avgEngagementValue = document.getElementById('kpi-avg-engagement-value');
        const passRateBadge = document.getElementById('kpi-pass-rate-badge');
        const completionRateBadge = document.getElementById('kpi-completion-rate-badge');
        const engagementBadge = document.getElementById('kpi-engagement-badge');
        
        const refreshKpiMetrics = async () => {
            try {
                const response = await fetch('{{ route('admin.dashboard.kpi-metrics') }}');
                if (!response.ok) throw new Error('Failed to fetch KPI metrics');
                const payload = await response.json();
                
                if (passRateValue) {
                    passRateValue.textContent = `${payload.passRate}%`;
                }
                if (completionRateValue) {
                    completionRateValue.textContent = `${payload.completionRate}%`;
                }
                if (avgEngagementValue) {
                    avgEngagementValue.innerHTML = `${payload.avgEngagement}<span class="fs-6">m</span>`;
                }
                
                if (passRateBadge) {
                    passRateBadge.textContent = 'Live';
                }
                if (completionRateBadge) {
                    completionRateBadge.textContent = 'Live';
                }
                if (engagementBadge) {
                    engagementBadge.textContent = 'Live';
                }
            } catch (error) {
                console.error(error);
                if (passRateBadge) {
                    passRateBadge.textContent = 'Live update failed';
                }
                if (completionRateBadge) {
                    completionRateBadge.textContent = 'Live update failed';
                }
                if (engagementBadge) {
                    engagementBadge.textContent = 'Live update failed';
                }
            }
        };
        
        if (passRateValue || completionRateValue || avgEngagementValue) {
            refreshKpiMetrics();
            setInterval(refreshKpiMetrics, 60000);
        }
        
        const materialsUsageCtx = document.getElementById('materialsUsageChart');
        const materialsUsageBadge = document.getElementById('materialsUsageBadge');
        let materialsUsageChart;
        
        const createMaterialsUsageChart = (labels, data) => {
            materialsUsageChart = new Chart(materialsUsageCtx, {
                type: 'bar',
                data: {
                    labels,
                    datasets: [{
                        label: 'Interactions',
                        data,
                        backgroundColor: ['#0d6efd', '#198754', '#ffc107', '#0dcaf0', '#6f42c1'],
                        borderRadius: 5
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { display: false }
                    },
                    scales: {
                        y: {
                            beginAtZero: true,
                            ticks: {
                                callback: function(value) {
                                    return value;
                                }
                            }
                        }
                    }
                }
            });
        };
        
        const refreshMaterialsUsage = async () => {
            try {
                const response = await fetch('{{ route('admin.dashboard.materials-usage-data') }}');
                if (!response.ok) throw new Error('Failed to fetch materials usage data');
                const payload = await response.json();
                
                if (!materialsUsageChart) {
                    createMaterialsUsageChart(payload.labels, payload.data);
                } else {
                    materialsUsageChart.data.labels = payload.labels;
                    materialsUsageChart.data.datasets[0].data = payload.data;
                    materialsUsageChart.update();
                }
                
                if (materialsUsageBadge) {
                    materialsUsageBadge.textContent = `Top subject: ${payload.topSubject}`;
                }
            } catch (error) {
                console.error(error);
                if (materialsUsageBadge) {
                    materialsUsageBadge.textContent = 'Live update failed';
                }
            }
        };
        
        if (materialsUsageCtx) {
            refreshMaterialsUsage();
            setInterval(refreshMaterialsUsage, 60000);
        }
        
        const gradeDistributionCtx = document.getElementById('gradeDistributionChart');
        const gradeDistributionBadge = document.getElementById('gradeDistributionBadge');
        let gradeDistributionChart;
        
        const createGradeDistributionChart = (labels, data) => {
            gradeDistributionChart = new Chart(gradeDistributionCtx, {
                type: 'doughnut',
                data: {
                    labels,
                    datasets: [{
                        data,
                        backgroundColor: ['#198754', '#0d6efd', '#fd7e14', '#dc3545'],
                        borderWidth: 0
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: { position: 'right' }
                    }
                }
            });
        };
        
        const refreshGradeDistribution = async () => {
            try {
                const response = await fetch('{{ route('admin.dashboard.grade-distribution-data') }}');
                if (!response.ok) throw new Error('Failed to fetch grade distribution data');
                const payload = await response.json();
                
                if (!gradeDistributionChart) {
                    createGradeDistributionChart(payload.labels, payload.data);
                } else {
                    gradeDistributionChart.data.labels = payload.labels;
                    gradeDistributionChart.data.datasets[0].data = payload.data;
                    gradeDistributionChart.update();
                }
                
                if (gradeDistributionBadge) {
                    gradeDistributionBadge.textContent = `Pass Rate ${payload.passRate}%`;
                }
            } catch (error) {
                console.error(error);
                if (gradeDistributionBadge) {
                    gradeDistributionBadge.textContent = 'Live update failed';
                }
            }
        };
        
        if (gradeDistributionCtx) {
            refreshGradeDistribution();
            setInterval(refreshGradeDistribution, 60000);
        }
    });
</script>
@endpush
@endsection
