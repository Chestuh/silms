@extends('layouts.app')

@section('title', 'Learning Path')

@section('content')
<h2 class="mb-4"><i class="bi bi-signpost-2 me-2"></i>Learning Path Guidance</h2>
<p class="text-muted small mb-3">Suggested learning sequence based on your performance, progress, and goals.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <ul class="list-group list-group-flush">
            @forelse($recommended as $r)
                <li class="list-group-item d-flex align-items-center {{ $r['locked'] ? 'list-group-item-secondary' : '' }}">
                    @if($r['completed'])
                        <i class="bi bi-check-circle-fill text-success me-3 fs-5"></i>
                    @elseif($r['locked'])
                        <i class="bi bi-lock-fill text-muted me-3 fs-5"></i>
                    @else
                        <i class="bi bi-circle text-muted me-3 fs-5"></i>
                    @endif
                    <div class="flex-grow-1">
                        <strong>{{ $r['material']->title }}</strong>
                        <span class="text-muted small ms-2">({{ $r['course']->code ?? '—' }})</span>
                        @if($r['progress'])
                            <span class="badge bg-secondary ms-2">{{ $r['progress']->progress_percent }}%</span>
                        @endif
                        @if($r['locked'] && $r['lock_reason'])
                            <br><small class="text-muted">{{ $r['lock_reason'] }}</small>
                        @endif
                    </div>
                    @if(!$r['completed'] && !$r['locked'])
                        <a href="{{ route('student.learning.material', $r['material']) }}" class="btn btn-sm btn-primary">Open</a>
                    @elseif($r['locked'])
                        <span class="btn btn-sm btn-outline-secondary disabled">Locked</span>
                    @endif
                </li>
            @empty
                <li class="list-group-item text-muted text-center py-4">No materials in your learning path. Enroll in courses to see recommendations.</li>
            @endforelse
        </ul>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('student.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
