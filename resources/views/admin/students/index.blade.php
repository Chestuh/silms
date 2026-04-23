@extends('layouts.app')

@section('title', 'Students')

@section('content')
<h2 class="mb-4"><i class="bi bi-person-badge me-2"></i>Students</h2>
<p class="text-muted small mb-3">View enrolled students and their information.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="get" action="{{ route('admin.students.index') }}" class="row g-2 mb-3 align-items-center">
            <div class="col-auto flex-grow-1">
                <input type="search" name="search" value="{{ request('search') }}" class="form-control" placeholder="Search by student number or name">
            </div>
            <div class="col-auto">
                <button type="submit" class="btn btn-primary">Search</button>
            </div>
            @if(request('search'))
                <div class="col-auto">
                    <a href="{{ route('admin.students.index') }}" class="btn btn-outline-secondary">Clear</a>
                </div>
            @endif
        </form>

        <div class="d-flex flex-column flex-sm-row justify-content-between align-items-sm-center mb-3">
            <p class="mb-0 small text-muted">
                Showing {{ $students->count() }} of {{ $students->total() }} students · Page {{ $students->currentPage() }} of {{ $students->lastPage() }}
            </p>
        </div>

        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Grade</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td class="fw-medium">{{ $s->student_number }}</td>
                            <td>{{ $s->user->name ?? '—' }}</td>
                            <td>Grade {{ (int)($s->year_level ?? 0) + 6 }}</td>
                            <td><span class="badge bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($s->status) }}</span></td>
                            <td>
                                <a href="{{ route('admin.students.show', $s) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                <a href="{{ route('admin.students.edit', $s) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No students.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($students->hasPages())
        <div class="card-footer">{{ $students->links('pagination::bootstrap-5') }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
