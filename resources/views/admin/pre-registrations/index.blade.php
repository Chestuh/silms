@extends('layouts.app')

@section('title', 'Pre-Registrations Management')

@section('content')
<div class="d-flex justify-content-between align-items-center mb-4">
    <h2>Pre-Registrations Management</h2>
</div>

<!-- Status Filter Tabs -->
<ul class="nav nav-tabs mb-4" role="tablist">
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $status === 'pending' ? 'active' : '' }}" href="{{ route('admin.pre-registrations.index', ['status' => 'pending']) }}">
            Pending <span class="badge bg-warning">{{ $counts['pending'] }}</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $status === 'approved' ? 'active' : '' }}" href="{{ route('admin.pre-registrations.index', ['status' => 'approved']) }}">
            Approved <span class="badge bg-success">{{ $counts['approved'] }}</span>
        </a>
    </li>
    <li class="nav-item" role="presentation">
        <a class="nav-link {{ $status === 'rejected' ? 'active' : '' }}" href="{{ route('admin.pre-registrations.index', ['status' => 'rejected']) }}">
            Rejected <span class="badge bg-danger">{{ $counts['rejected'] }}</span>
        </a>
    </li>
</ul>

@if(session('success'))
    <div class="alert alert-success alert-dismissible fade show" role="alert">
        {{ session('success') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

@if($preRegistrations->count())
    <div class="card">
        <div class="table-responsive">
            <table class="table table-hover mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Full Name</th>
                        <th>Email</th>
                        <th>Program</th>
                        <th>Year Level</th>
                        <th>Submitted</th>
                        <th>Status</th>
                        <th>Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($preRegistrations as $reg)
                        <tr>
                            <td class="fw-500">{{ $reg->full_name }}</td>
                            <td>{{ $reg->email }}</td>
                            <td>{{ $reg->display_program }}</td>
                            <td><span class="badge bg-info">{{ $reg->year_level_label }}</span></td>
                            <td>{{ \Carbon\Carbon::parse($reg->created_at)->format('M d, Y') }}</td>
                            <td>
                                @if($reg->status === 'pending')
                                    <span class="badge bg-warning">Pending</span>
                                @elseif($reg->status === 'approved')
                                    <span class="badge bg-success">Approved</span>
                                @else
                                    <span class="badge bg-danger">Rejected</span>
                                @endif
                            </td>
                            <td>
                                <a href="{{ route('admin.pre-registrations.show', $reg) }}" class="btn btn-sm btn-info">
                                    <i class="bi bi-eye"></i> View
                                </a>
                                @if($reg->status === 'pending')
                                    <form action="{{ route('admin.pre-registrations.approve', $reg) }}" method="POST" style="display:inline;">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success" onclick="return confirm('Approve this pre-registration?')">
                                            <i class="bi bi-check-lg"></i> Approve
                                        </button>
                                    </form>
                                    <button class="btn btn-sm btn-danger" onclick="rejectModal({{ $reg->id }})">
                                        <i class="bi bi-x-lg"></i> Reject
                                    </button>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <!-- Pagination -->
    <div class="mt-4">
        {{ $preRegistrations->appends(request()->query())->links() }}
    </div>
@else
    <div class="alert alert-info">
        No {{ $status }} pre-registrations at this time.
    </div>
@endif

<!-- Reject Modal -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Reject Pre-Registration</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <form id="rejectForm" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label class="form-label">Rejection Reason</label>
                        <textarea name="rejection_reason" class="form-control" rows="4" required placeholder="Explain why this pre-registration is being rejected..."></textarea>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
const rejectModalElement = document.getElementById('rejectModal');
const rejectModal = new bootstrap.Modal(rejectModalElement);

function rejectModal(id) {
    const form = document.getElementById('rejectForm');
    form.action = `/admin/pre-registrations/${id}/reject`;
    rejectModal.show();
}
</script>
@endpush

@endsection
