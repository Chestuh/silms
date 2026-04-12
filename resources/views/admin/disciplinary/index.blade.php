@extends('layouts.app')

@section('title', 'Student Disciplinary Records')

@section('content')
<h2 class="mb-4"><i class="bi bi-shield-exclamation me-2"></i>Student Disciplinary Records</h2>
<p class="text-muted small mb-3">Violations, sanctions, and disciplinary actions for conduct monitoring.</p>

<div class="mb-3"><a href="{{ route('admin.disciplinary.create') }}" class="btn btn-primary">Add disciplinary record</a></div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Description</th>
                        <th>Sanction</th>
                        <th>Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ ($r->incident_date ?? null) ? \Carbon\Carbon::parse($r->incident_date)->format('M j, Y') : '—' }}</td>
                            <td>{{ $r->student->user->name ?? '—' }} <small class="text-muted">({{ $r->student->student_number }})</small></td>
                            <td class="small">{{ Str::limit($r->description, 50) }}</td>
                            <td class="small">{{ Str::limit($r->sanction, 30) ?: '—' }}</td>
                            <td><span class="badge bg-{{ $r->status === 'resolved' ? 'success' : ($r->status === 'appealed' ? 'info' : 'warning') }}">{{ ucfirst($r->status) }}</span></td>
                            <td>
                                <a href="{{ route('admin.disciplinary.edit', $r) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                <form method="POST" action="{{ route('admin.disciplinary.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete this record?');">@csrf @method('DELETE')<button type="submit" class="btn btn-sm btn-outline-danger">Delete</button></form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No disciplinary records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
        <div class="card-footer">{{ $records->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
