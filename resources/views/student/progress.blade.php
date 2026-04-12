@extends('layouts.app')

@section('title', 'Learning Progress')

@section('content')
<h2 class="mb-4"><i class="bi bi-graph-up me-2"></i>Learning Progress</h2>
<div class="row g-3 mb-4">
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Materials started</h6>
                <h4 class="mb-0">{{ $progress->count() }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card success">
            <div class="card-body">
                <h6 class="text-muted">Completed</h6>
                <h4 class="mb-0">{{ $completed }}</h4>
            </div>
        </div>
    </div>
    <div class="col-md-4">
        <div class="card stat-card">
            <div class="card-body">
                <h6 class="text-muted">Total time spent</h6>
                <h4 class="mb-0">{{ $totalTime }} min</h4>
            </div>
        </div>
    </div>
</div>
<div class="card">
    <div class="card-header">Track time spent per activity</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Material</th><th>Course</th><th>Format</th><th>Difficulty</th><th>Progress</th><th>Time (min)</th><th>Completed</th></tr></thead>
            <tbody>
                @foreach($progress as $p)
                <tr>
                    <td>{{ $p->material->title }}</td>
                    <td>{{ optional($p->material->course)->code ?? '—' }}</td>
                    <td>{{ $p->material->format }}</td>
                    <td><span class="badge badge-difficulty-{{ $p->material->difficulty_level }}">{{ $p->material->difficulty_level }}</span></td>
                    <td><div class="progress" style="width:80px"><div class="progress-bar" style="width:{{ $p->progress_percent }}%">{{ $p->progress_percent }}%</div></div></td>
                    <td>{{ $p->time_spent_minutes }}</td>
                    <td>{{ $p->completed_at ? \Carbon\Carbon::parse($p->completed_at)->format('M j, Y') : '—' }}</td>
                </tr>
                @endforeach
                @if($progress->isEmpty())
                <tr><td colspan="7" class="text-center text-muted">No progress recorded.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
