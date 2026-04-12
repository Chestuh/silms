@extends('layouts.app')

@section('title', 'Global Learning Progress')

@section('content')
<h2 class="mb-4"><i class="bi bi-graph-up me-2"></i>Track learning progress</h2>
<p class="text-muted small mb-3">System-wide view of student learning progress.</p>

<div class="row g-3 mb-4">
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-primary bg-opacity-10 rounded-3 p-2"><i class="bi bi-journal-check text-primary fs-4"></i></div>
                <div>
                    <div class="fw-bold fs-4">{{ $globalStats['total_progress_records'] }}</div>
                    <div class="small text-muted">Total progress records</div>
                </div>
            </div>
        </div>
    </div>
    <div class="col-md-6">
        <div class="card border-0 shadow-sm">
            <div class="card-body d-flex align-items-center gap-3">
                <div class="bg-success bg-opacity-10 rounded-3 p-2"><i class="bi bi-check-circle text-success fs-4"></i></div>
                <div>
                    <div class="fw-bold fs-4">{{ $globalStats['total_completed'] }}</div>
                    <div class="small text-muted">Completed (100%)</div>
                </div>
            </div>
        </div>
    </div>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th class="text-center">Progress records</th>
                        <th class="text-center">Completed</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td class="fw-medium">{{ $s->student_number }}</td>
                            <td>{{ $s->user->name ?? '—' }}</td>
                            <td class="text-center">{{ $s->total_progress_count ?? 0 }}</td>
                            <td class="text-center">{{ $s->completed_count ?? 0 }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No students.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($students->hasPages())
        <div class="card-footer">{{ $students->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
