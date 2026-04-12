@extends('layouts.app')

@section('title', 'Students — View only')

@section('content')
<h2 class="mb-4"><i class="bi bi-people me-2"></i>Students</h2>
<p class="text-muted small mb-3">View-only access for clearance and verification.</p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Program</th>
                        <th>Year</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td>{{ $s->student_number }}</td>
                            <td class="fw-medium">{{ $s->user->name ?? '—' }}</td>
                            <td>{{ $s->program ?? '—' }}</td>
                            <td>{{ $s->year_level ?? '—' }}</td>
                            <td><span class="badge bg-{{ $s->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($s->status) }}</span></td>
                            <td><a href="{{ route('cashier.students.show', $s) }}" class="btn btn-sm btn-outline-primary">View</a></td>
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
<p class="small text-muted mt-2 mb-0"><a href="{{ route('cashier.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
