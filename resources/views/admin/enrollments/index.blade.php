@extends('layouts.app')

@section('title', 'Enrollments')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Enrollments</h2>
<p class="text-muted small mb-3">View enrollees, important information, and attachments. Approve or reject.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Course</th>
                        <th>Semester / Year</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($enrollments as $e)
                        <tr>
                            <td>{{ $e->student->user->name ?? '—' }}</td>
                            <td>{{ $e->course->code ?? '—' }} — {{ $e->course->title ?? '—' }}</td>
                            <td>{{ $e->semester ?? '—' }} / {{ $e->school_year ?? '—' }}</td>
                            <td><span class="badge bg-{{ $e->status === 'enrolled' ? 'success' : 'secondary' }}">{{ ucfirst($e->status) }}</span></td>
                            <td>
                                <a href="{{ route('admin.enrollments.show', $e) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                @if($e->status === 'enrolled')
                                    <form method="POST" action="{{ route('admin.enrollments.reject', $e) }}" class="d-inline" onsubmit="return confirm('Drop this enrollment?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Drop</button>
                                    </form>
                                @else
                                    <form method="POST" action="{{ route('admin.enrollments.approve', $e) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No enrollments.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($enrollments->hasPages())
        <div class="card-footer">{{ $enrollments->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
