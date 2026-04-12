@extends('layouts.app')

@section('title', 'Learning Materials')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-book me-2"></i>Learning Materials</h2>
<p class="text-muted">Multi-format support. Track progress and rate materials. Learning path guidance below.</p>

@if(isset($recommendedNext) && $recommendedNext->isNotEmpty())
<div class="card border-primary mb-4">
    <div class="card-header bg-primary text-white"><i class="bi bi-signpost-2 me-2"></i>Learning path — recommended next</div>
    <div class="card-body">
        <p class="small text-muted mb-2">Continue with these to stay on track:</p>
        <ul class="list-unstyled mb-0">
            @foreach($recommendedNext as $m)
            <li class="mb-2">
                <a href="{{ route('student.learning.material', $m) }}">{{ $m->title }}</a>
                <span class="text-muted small"> — {{ optional($m->course)->code ?? 'General' }} · {{ $m->progress && $m->progress->progress_percent ? $m->progress->progress_percent : 0 }}%</span>
            </li>
            @endforeach
        </ul>
    </div>
</div>
@endif

<div class="row g-3">
    @foreach($materials as $m)
    @php $p = $m->progress; @endphp
    <div class="col-md-6 col-lg-4">
        <div class="card h-100">
            <div class="card-body">
                <span class="badge bg-secondary mb-2">{{ $m->format }}</span>
                <span class="badge badge-difficulty-{{ $m->difficulty_level }} mb-2">{{ $m->difficulty_level }}</span>
                <h6 class="card-title">{{ $m->title }}</h6>
                <p class="small text-muted mb-2">{{ optional($m->course)->code ?? 'General' }} — {{ optional($m->course)->title ?? '' }}</p>
                @if($m->description)<p class="small mb-2">{{ Str::limit($m->description, 80) }}</p>@endif
                <div class="mb-2">
                    <small>Progress</small>
                    <div class="progress"><div class="progress-bar" style="width:{{ $p ? $p->progress_percent : 0 }}%">{{ $p ? $p->progress_percent : 0 }}%</div></div>
                </div>
                <small class="text-muted">Time spent: {{ $p ? $p->time_spent_minutes : 0 }} min</small>
            </div>
            <div class="card-footer bg-transparent">
                <a href="{{ route('student.learning.material', $m) }}" class="btn btn-sm btn-primary">Open</a>
                <a href="{{ route('student.rate.edit', $m) }}" class="btn btn-sm btn-outline-secondary">Rate</a>
            </div>
        </div>
    </div>
    @endforeach
</div>
@if($materials->isEmpty())
<div class="alert alert-info">No learning materials available yet.</div>
@endif
@endsection
