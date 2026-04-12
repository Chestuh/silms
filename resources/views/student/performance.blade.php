@extends('layouts.app')

@section('title', 'Performance and Reports')

@section('content')
<h2 class="mb-4"><i class="bi bi-bar-chart me-2"></i>Student Performance Statistics and History</h2>

<section class="mb-4">
    <h5 class="text-muted mb-3">Dashboard with KPIs</h5>
    <div class="row g-3">
        <div class="col-6 col-md-4">
            <div class="card stat-card h-100"><div class="card-body"><h6 class="text-muted">Activities started</h6><h4 class="mb-0">{{ $activities }}</h4></div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stat-card h-100"><div class="card-body"><h6 class="text-muted">Total time spent (min)</h6><h4 class="mb-0">{{ $totalTime }}</h4></div></div>
        </div>
        <div class="col-6 col-md-4">
            <div class="card stat-card success h-100"><div class="card-body"><h6 class="text-muted">Average grade</h6><h4 class="mb-0">{{ $avgGrade ? number_format($avgGrade, 1) : '—' }}</h4></div></div>
        </div>
    </div>
</section>

<div class="card mb-4">
    <div class="card-header bg-transparent"><strong>Academic completion status</strong></div>
    <div class="card-body">
        <p class="mb-2">You have completed <strong>{{ $completed ?? 0 }}</strong> of <strong>{{ $activities }}</strong> learning activities.</p>
        <div class="progress" style="height: 1.5rem;">
            <div class="progress-bar bg-success" style="width: {{ $completionPercent ?? 0 }}%;">{{ $completionPercent ?? 0 }}%</div>
        </div>
        <p class="small text-muted mt-2 mb-0">Track time spent per activity in <a href="{{ route('student.progress') }}">Learning Progress</a>.</p>
    </div>
</div>

<div class="card mb-4">
    <div class="card-header bg-transparent"><strong>Skill gaps and improvement report</strong></div>
    <div class="card-body">
        <p class="text-muted small mb-2">Focus on these to improve completion:</p>
        @if(isset($gaps) && $gaps->isNotEmpty())
        <ul class="list-unstyled mb-0">
            @foreach($gaps as $p)
            <li class="mb-2 d-flex justify-content-between align-items-center">
                <span>{{ $p->material->title ?? '—' }}</span>
                <span class="badge bg-{{ $p->progress_percent >= 50 ? 'warning' : 'secondary' }}">{{ $p->progress_percent }}%</span>
            </li>
            @endforeach
        </ul>
        <p class="small text-muted mt-2 mb-0">Complete these materials in <a href="{{ route('student.learning.index') }}">Learning Materials</a> to close gaps.</p>
        @else
        <p class="text-muted mb-0">No significant gaps. Keep up the good work or start more activities in <a href="{{ route('student.learning.index') }}">Learning Materials</a>.</p>
        @endif
    </div>
</div>

<div class="card">
    <div class="card-header bg-transparent">Reports and history</div>
    <div class="card-body">
        <p class="text-muted mb-0">Activity history and time spent per activity are tracked in <a href="{{ route('student.progress') }}">Learning Progress</a>. Grades and GWA are in <a href="{{ route('student.gwa') }}">GWA & Grades</a>.</p>
    </div>
</div>
@endsection
