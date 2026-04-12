@extends('layouts.app')

@section('title', 'Student Progress')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard-data me-2"></i>View student progress</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Student</th>
                        <th>Material</th>
                        <th>Course</th>
                        <th class="text-center">Progress</th>
                        <th class="text-center">Time spent</th>
                        <th>Completed</th>
                        <th>Last updated</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($progress as $p)
                        <tr>
                            <td>{{ $p->student->user->name ?? '—' }}</td>
                            <td>{{ $p->material->title ?? '—' }}</td>
                            <td><span class="text-muted small">{{ $p->material->course->code ?? '—' }}</span></td>
                            <td class="text-center">
                                <span class="badge {{ $p->progress_percent >= 100 ? 'bg-success' : 'bg-secondary' }}">{{ $p->progress_percent }}%</span>
                            </td>
                            <td class="text-center">{{ $p->time_spent_minutes ?? 0 }} min</td>
                            <td>{{ $p->completed_at ? \Carbon\Carbon::parse($p->completed_at)->format('M j, Y') : '—' }}</td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($p->updated_at)->format('M j, Y') }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="7" class="text-muted text-center py-4">No progress records yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $progress->links() }}
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
