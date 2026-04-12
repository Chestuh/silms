@extends('layouts.app')

@section('title', 'Skill Gaps & Improvement Report')

@section('content')
<h2 class="mb-4"><i class="bi bi-graph-down me-2"></i>Skill Gaps & Improvement Report</h2>
<p class="text-muted small mb-3">Identify weak areas and recommend improvements based on progress data.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        @forelse($byStudent as $item)
            <div class="border-bottom p-3">
                <div class="d-flex justify-content-between align-items-start flex-wrap gap-2">
                    <div>
                        <strong>{{ $item['student']->user->name ?? '—' }}</strong>
                        <span class="text-muted small">({{ $item['student']->student_number }})</span>
                    </div>
                    <span class="badge {{ $item['completion_pct'] >= 80 ? 'bg-success' : ($item['completion_pct'] >= 50 ? 'bg-warning text-dark' : 'bg-danger') }}">{{ $item['completion_pct'] }}% completion</span>
                </div>
                <p class="small text-muted mb-1 mt-1">{{ $item['completed'] }} / {{ $item['total'] }} materials completed.</p>
                @if($item['weak_areas']->isNotEmpty())
                    <p class="small mb-0"><strong>Areas to improve:</strong>
                        @foreach($item['weak_areas'] as $p)
                            {{ $p->material->title ?? '—' }} ({{ $p->progress_percent }}%)@if(!$loop->last), @endif
                        @endforeach
                    </p>
                @endif
            </div>
        @empty
            <div class="p-4 text-center text-muted">No student progress data yet.</div>
        @endforelse
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
