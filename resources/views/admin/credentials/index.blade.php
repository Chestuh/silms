@extends('layouts.app')

@section('title', 'Credential Requests')

@section('content')
<h2 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i>Credential requests</h2>
<p class="text-muted small mb-3">Pending and processing requests. Send a signed letter (PDF) to the registrar for each request.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Student No.</th>
                        <th>Credential type</th>
                        <th>Status</th>
                        <th>Requested</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        <tr>
                            <td class="fw-medium">{{ $r->student->user->name ?? '—' }}</td>
                            <td>{{ $r->student->student_number ?? '—' }}</td>
                            <td>{{ $r->credential_type }}</td>
                            <td><span class="badge bg-{{ $r->status === 'processing' ? 'info' : 'warning' }}">{{ ucfirst($r->status) }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('M j, Y') }}</td>
                            <td>
                                <a href="{{ route('admin.credentials.show', $r) }}" class="btn btn-sm btn-outline-primary me-1">View</a>
                                <a href="{{ route('admin.credentials.letter', $r) }}" class="btn btn-sm btn-success" target="_blank"><i class="bi bi-file-pdf me-1"></i>Letter (PDF)</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No pending credential requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
        <div class="card-footer">{{ $requests->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
