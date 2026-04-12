@extends('layouts.app')

@section('title', 'Students')

@section('content')
<h2 class="mb-4"><i class="bi bi-person-badge me-2"></i>Students</h2>
<p class="text-muted small mb-3">View enrolled students and their information.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
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
                        <tr><td colspan="6" class="text-center text-muted py-4">No students.</td></tr>
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
