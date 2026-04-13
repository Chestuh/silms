@extends('layouts.app')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-3">
        <h3 class="mb-0">Transfer Requests</h3>
        <div class="btn-group" role="group" aria-label="Transfer request filters">
            <a href="{{ route('admin.transfer-requests.index') }}" class="btn btn-sm {{ empty($status) ? 'btn-secondary' : 'btn-outline-secondary' }}">
                All <span class="badge badge-contrast ms-1">{{ $counts['all'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.transfer-requests.index', ['status' => 'pending']) }}" class="btn btn-sm {{ $status === 'pending' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                Pending <span class="badge badge-contrast ms-1">{{ $counts['pending'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.transfer-requests.index', ['status' => 'approved']) }}" class="btn btn-sm {{ $status === 'approved' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                Approved <span class="badge badge-contrast ms-1">{{ $counts['approved'] ?? 0 }}</span>
            </a>
            <a href="{{ route('admin.transfer-requests.index', ['status' => 'rejected']) }}" class="btn btn-sm {{ $status === 'rejected' ? 'btn-secondary' : 'btn-outline-secondary' }}">
                Rejected <span class="badge badge-contrast ms-1">{{ $counts['rejected'] ?? 0 }}</span>
            </a>
        </div>
    </div>
    @if(session('success'))<div class="alert alert-success">{{ session('success') }}</div>@endif
    <table class="table">
        <thead><tr><th>ID</th><th>Student</th><th>From</th><th>To</th><th>Status</th><th>Requested</th><th>Actions</th></tr></thead>
        <tbody>
        @forelse($requests as $r)
            <tr>
                <td>{{ $r->id }}</td>
                <td>{{ optional($r->student->user)->name ?? optional($r->student->user)->email ?? '—' }}</td>
                <td>{{ $r->from_school ?? '—' }}</td>
                <td>{{ $r->to_school ?? '—' }}</td>
                <td>{{ ucfirst($r->status) }}</td>
                <td>{{ optional($r->requested_at)->format('M j, Y H:i') ?? '—' }}</td>
                <td>
                    <a href="{{ route('admin.transfer-requests.show', $r) }}" class="btn btn-sm btn-outline-primary">View</a>
                    @if($r->status === 'pending')
                        <form method="POST" action="{{ route('admin.transfer-requests.approve', $r) }}" style="display:inline">@csrf<button class="btn btn-sm btn-success">Approve</button></form>
                        <form method="POST" action="{{ route('admin.transfer-requests.reject', $r) }}" style="display:inline">@csrf<button class="btn btn-sm btn-danger">Reject</button></form>
                    @endif
                </td>
            </tr>
        @empty
            <tr>
                <td colspan="7" class="text-center text-muted">No transfer requests found.</td>
            </tr>
        @endforelse
        </tbody>
    </table>
</div>
@endsection
