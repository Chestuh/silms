@extends('layouts.app')

@section('title', 'Student Dashboard')

@section('content')
@php
    $pref = auth()->user()->dashboardPreference;
    $themeColor = optional($pref)->theme_color ?: '#0d6efd';
    $heroTextColor = '#ffffff';
    $showQuickLinks = optional($pref)->show_quick_links ?? true;
    $showMyInfo = optional($pref)->show_my_info ?? true;
@endphp
<div class="instructor-welcome rounded-4 p-5 mb-4" style="position: relative; overflow: hidden; background: linear-gradient(135deg, {{ $themeColor }} 0%, rgba(255,255,255,0.14) 100%); color: {{ $heroTextColor }};">
    <div style="position: relative; z-index: 1;" class="d-flex flex-wrap align-items-center justify-content-between gap-3">
        <div>
            <h2 class="mb-2" style="font-weight: 700; font-size: 2rem;">Welcome back, {{ auth()->user()->name }}</h2>
            <p style="font-size: 1.05rem; opacity: 0.9; margin: 0; color: rgba(255,255,255,0.9);">{{ $student->program ?? 'Student' }}</p>
            @if($pref && $pref->learning_focus)
                <div class="alert alert-light mt-2 py-2 small" style="background: rgba(255,255,255,0.18); border: 1px solid rgba(255,255,255,0.2); color: {{ $heroTextColor }};">
                    <i class="bi bi-bullseye me-1"></i>
                    <strong>Learning focus:</strong> {{ $pref->learning_focus }}
                </div>
            @endif
            @if($pref && $pref->layout !== 'default')
                <div class="alert alert-light mt-2 py-2 small" style="background: rgba(255,255,255,0.14); border: 1px solid rgba(255,255,255,0.18); color: {{ $heroTextColor }};">
                    <i class="bi bi-layout-sidebar me-1"></i>
                    <strong>Dashboard layout:</strong> {{ ucfirst($pref->layout) }}
                </div>
            @endif
            <a href="{{ route('student.dashboard-personalization') }}" class="btn btn-outline-light btn-sm mt-2"><i class="bi bi-sliders me-1"></i>Personalize Dashboard</a>
        </div>
    </div>
    <div style="position: absolute; top: -40px; right: -100px; width: 350px; height: 350px; background: rgba(255,255,255,0.15); border-radius: 50%; z-index: 0;"></div>
</div>

@if(isset($kpis['load_valid']) && !$kpis['load_valid'])
<div class="alert alert-warning mb-4">
    <i class="bi bi-exclamation-triangle me-2"></i><strong>Academic load validation:</strong> Your current enrolled units ({{ $kpis['current_units'] }}) exceed the maximum allowed ({{ $kpis['max_units'] }}). Please contact your adviser.
</div>
@endif
@if(isset($kpis['current_units']))
<div class="alert alert-light border mb-4 py-2 small">
    <strong>Academic load:</strong> {{ $kpis['current_units'] }} / {{ $kpis['max_units'] }} units this term.
</div>
@endif

<section class="mb-4">
    <h5 class="text-muted mb-3"><i class="bi bi-graph-up-arrow me-2"></i>Key Performance Indicators</h5>
    @php
        $pref = auth()->user()->dashboardPreference;
        $widgetCards = $widgetCards ?? [];
        $compactCard = $widgetCards[0] ?? null;
    @endphp
    @if($pref && $pref->layout === 'compact')
        <div class="row g-3">
            <div class="col-12">
                @if($compactCard)
                    <div class="card kpi-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center py-3">
                            <div class="kpi-value">{{ $compactCard['value'] !== null ? $compactCard['value'] : '—' }}</div>
                            <div class="kpi-label small text-muted">{{ $compactCard['label'] }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">No KPI widgets are enabled. Update your dashboard personalization settings to show widgets.</div>
                @endif
            </div>
        </div>
    @elseif($pref && $pref->layout === 'focus')
        <div class="row g-3">
            <div class="col-12">
                @if($compactCard)
                    <div class="card kpi-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center py-3">
                            <div class="kpi-value">{{ $compactCard['value'] !== null ? $compactCard['value'] : '—' }}</div>
                            <div class="kpi-label small text-muted">{{ $compactCard['label'] }}</div>
                        </div>
                    </div>
                @else
                    <div class="alert alert-info">No KPI widgets are enabled. Update your dashboard personalization settings to show widgets.</div>
                @endif
            </div>
        </div>
    @else
        <div class="row g-3">
            @forelse($widgetCards as $card)
                <div class="col-6 col-md-4 col-lg-2">
                    <div class="card kpi-card h-100 border-0 shadow-sm">
                        <div class="card-body text-center py-3">
                            <div class="kpi-value">{{ $card['value'] !== null ? $card['value'] : '—' }}</div>
                            <div class="kpi-label small text-muted">{{ $card['label'] }}</div>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12">
                    <div class="alert alert-info">No KPI widgets are enabled. Update your dashboard personalization settings to show widgets.</div>
                </div>
            @endforelse
        </div>
    @endif
</section>

<div class="row">
    @if($showQuickLinks)
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header" style="background-color: {{ $themeColor }}; color: #fff;">Quick Links</div>
                <div class="list-group list-group-flush">
                    <a href="{{ route('student.gwa') }}" class="list-group-item list-group-item-action"><i class="bi bi-calculator me-2"></i>GWA & Grades</a>
                    <a href="{{ route('student.academic-status') }}" class="list-group-item list-group-item-action"><i class="bi bi-clipboard-check me-2"></i>Academic Status</a>
                    <a href="{{ route('student.learning.index') }}" class="list-group-item list-group-item-action"><i class="bi bi-bar-chart-line me-2"></i>Learning Materials</a>
                    <a href="{{ route('student.progress') }}" class="list-group-item list-group-item-action"><i class="bi bi-graph-up me-2"></i>Learning Progress</a>
                    <a href="{{ route('student.messages') }}" class="list-group-item list-group-item-action"><i class="bi bi-chat-dots me-2"></i>Messages</a>
                    <a href="{{ route('student.fees') }}" class="list-group-item list-group-item-action"><i class="bi bi-credit-card me-2"></i>Fees & Payments</a>
                </div>
            </div>
        </div>
    @endif
    @if($showMyInfo)
        <div class="col-lg-6 mb-4">
            <div class="card">
                <div class="card-header" style="background-color: {{ $themeColor }}; color: #fff;">My Info</div>
                <ul class="list-group list-group-flush">
                    <li class="list-group-item d-flex justify-content-between"><span>Student No.</span><strong>{{ $student->student_number ?? '—' }}</strong></li>
                    @php
                        $showProgram = false;
                        // If student year_level indicates Senior High (11 or 12), show program.
                        // Otherwise (Grade 7-10) do not show the Program row.
                        $yr = (int) ($student->year_level ?? 0);
                        if ($yr >= 11) {
                            $showProgram = true;
                        }
                    @endphp
                    @if($showProgram)
                        <li class="list-group-item d-flex justify-content-between"><span>Program</span><strong>{{ $student->program ?? '—' }}</strong></li>
                    @endif
                    <li class="list-group-item d-flex justify-content-between"><span>Year Level</span><strong>{{ $student->year_level }}</strong></li>
                    <li class="list-group-item d-flex justify-content-between"><span>Status</span><span class="badge" style="background-color: {{ $themeColor }};">{{ ucfirst($student->status) }}</span></li>
                </ul>
            </div>
        </div>
    @endif
</div>
@endsection
