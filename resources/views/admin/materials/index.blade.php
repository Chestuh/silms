@extends('layouts.app')

@section('title', 'Materials')

@section('content')
<h2 class="mb-4"><i class="bi bi-folder2-open me-2"></i>Learning Materials</h2>
<!-- <p class="text-muted small mb-3">View materials added by instructors. Approve or reject; add a comment when rejecting.</p> -->

<ul class="nav nav-tabs mb-3">
    <li class="nav-item">
        <a class="nav-link {{ ($activeTab ?? 'all') === 'all' ? 'active' : '' }}" href="{{ route('admin.materials.index', ['tab' => 'all']) }}">Materials</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ ($activeTab ?? '') === 'archived' ? 'active' : '' }}" href="{{ route('admin.materials.index', ['tab' => 'archived']) }}">Archived</a>
    </li>
    <li class="nav-item">
        <a class="nav-link {{ ($activeTab ?? '') === 'rejected' ? 'active' : '' }}" href="{{ route('admin.materials.index', ['tab' => 'rejected']) }}">Rejected</a>
    </li>
</ul>

<!-- Search Filter -->
<form method="GET" class="row gx-2 gy-2 align-items-center mb-3">
    <input type="hidden" name="tab" value="{{ $activeTab ?? 'all' }}">
    <div class="col-auto">
        <label for="search" class="form-label visually-hidden">Search</label>
        <div class="input-group">
            <input type="text" name="search" id="search" class="form-control" 
                placeholder="Search materials, course, or instructor..." 
                value="{{ $search ?? '' }}">
            <button type="submit" class="btn btn-outline-secondary">
                <i class="bi bi-search"></i> Search
            </button>
            @if($search ?? false)
                <a href="{{ route('admin.materials.index', ['tab' => $activeTab ?? 'all']) }}" class="btn btn-outline-secondary">
                    <i class="bi bi-x-lg"></i> Clear
                </a>
            @endif
        </div>
    </div>
</form>

@if(($activeTab ?? 'all') === 'all')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th>Status</th>
                        <th>Archive</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $m)
                        <tr>
                            <td class="fw-medium">{{ $m->title }}</td>
                            <td>{{ $m->course->code ?? '—' }}</td>
                            <td>{{ $m->course->instructor->user->name ?? '—' }}</td>
                            <td>
                                @php $status = $m->approval_status ?? 'pending'; @endphp
                                <span class="badge bg-{{ $status === 'approved' ? 'success' : ($status === 'rejected' ? 'danger' : 'warning') }}">{{ ucfirst($status) }}</span>
                                @if($m->admin_comment)
                                    <br><small class="text-muted">{{ Str::limit($m->admin_comment, 40) }}</small>
                                @endif
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.materials.archive', $m) }}" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-outline-secondary">Archive</button></form>
                            </td>
                            <td>
                                @if(($m->approval_status ?? 'pending') === 'pending')
                                    <form method="POST" action="{{ route('admin.materials.approve', $m) }}" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Approve</button>
                                    </form>
                                    <button type="button" class="btn btn-sm btn-danger btn-reject" data-id="{{ $m->id }}" data-title="{{ $m->title }}">Reject</button>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No materials.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        @if($materials->hasPages())
            <div class="card-footer">{{ $materials->appends(['tab' => 'all', 'search' => $search ?? ''])->links() }}</div>
        @endif
    </div>
    <p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endif

@if(($activeTab ?? '') === 'archived')
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Title</th>
                        <th>Course</th>
                        <th>Instructor</th>
                        <th>Status</th>
                        <th>Action</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $m)
                        <tr class="table-secondary">
                            <td class="fw-medium">{{ $m->title }}</td>
                            <td>{{ $m->course->code ?? '—' }}</td>
                            <td>{{ $m->course->instructor->user->name ?? '—' }}</td>
                            <td>
                                <span class="badge bg-secondary">Archived</span>
                            </td>
                            <td>
                                <form method="POST" action="{{ route('admin.materials.unarchive', $m) }}" class="d-inline">@csrf<button type="submit" class="btn btn-sm btn-outline-success">Restore</button></form>
                            </td>
                            <td><span class="text-muted small"></span></td>
                        </tr>
                    @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">No archived materials.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
        @if($materials->hasPages())
            <div class="card-footer">{{ $materials->appends(['tab' => 'archived', 'search' => $search ?? ''])->links() }}</div>
        @endif
    </div>
    <p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endif

@if(($activeTab ?? '') === 'rejected')
    <div class="mb-4">
        <h3 class="mb-3"><i class="bi bi-x-circle me-2 text-danger"></i>Rejected materials</h3>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover align-middle mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Title</th>
                                <th>Course</th>
                                <th>Instructor</th>
                                <th>Comment</th>
                                <th class="text-end">Actions</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($rejectedMaterials as $m)
                                <tr>
                                    <td class="fw-medium">{{ $m->title }}</td>
                                    <td>{{ $m->course->code ?? '—' }}</td>
                                    <td>{{ $m->course->instructor->user->name ?? '—' }}</td>
                                    <td><small class="text-muted">{{ $m->admin_comment }}</small></td>
                                    <td class="text-end">
                                <button type="button" class="btn btn-sm btn-danger btn-delete-material" data-id="{{ $m->id }}" data-title="{{ $m->title }}">Delete</button>
                            </td>
                        </tr>
                            @empty
                                <tr><td colspan="5" class="text-center text-muted py-4">No rejected materials.</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if($rejectedMaterials->hasPages())
                <div class="card-footer">{{ $rejectedMaterials->appends(['tab' => 'rejected', 'search' => $search ?? ''])->links() }}</div>
            @endif
        </div>
    </div>
@endif

<!-- Reusable reject modal (single instance) -->
<div class="modal fade" id="rejectModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="rejectForm" method="POST" action="{{ route('admin.materials.reject', 0) }}">
                @csrf
                <div class="modal-header">
                    <h5 class="modal-title">Reject material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="mb-2">
                        <strong id="rejectMaterialTitle"></strong>
                    </div>
                    <label class="form-label">Comment (required)</label>
                    <textarea name="admin_comment" id="rejectComment" class="form-control" rows="3" required placeholder="Reason for rejection..."></textarea>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Reject</button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reusable delete modal (single instance) -->
<div class="modal fade" id="deleteModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form id="deleteForm" method="POST" action="{{ route('admin.materials.destroy', 0) }}">
                @csrf
                @method('DELETE')
                <div class="modal-header">
                    <h5 class="modal-title">Delete material</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Are you sure you want to permanently delete this rejected material?</p>
                    <p><strong id="deleteMaterialTitle"></strong></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-danger">Delete permanently</button>
                </div>
            </form>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.addEventListener('DOMContentLoaded', function() {
    const rejectModalEl = document.getElementById('rejectModal');
    const deleteModalEl = document.getElementById('deleteModal');
    const rejectForm = document.getElementById('rejectForm');
    const rejectTitle = document.getElementById('rejectMaterialTitle');
    const rejectComment = document.getElementById('rejectComment');
    const deleteForm = document.getElementById('deleteForm');
    const deleteTitle = document.getElementById('deleteMaterialTitle');

    if (rejectModalEl) {
        const bsRejectModal = new bootstrap.Modal(rejectModalEl);
        document.querySelectorAll('.btn-reject').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title') || '';
                rejectForm.action = rejectForm.action.replace('/0/reject', `/${id}/reject`);
                rejectTitle.textContent = title;
                rejectComment.value = '';
                bsRejectModal.show();
            });
        });

        rejectModalEl.addEventListener('hidden.bs.modal', function() {
            rejectForm.action = `{{ route('admin.materials.reject', 0) }}`;
            rejectComment.value = '';
        });
    }

    if (deleteModalEl) {
        const bsDeleteModal = new bootstrap.Modal(deleteModalEl);
        document.querySelectorAll('.btn-delete-material').forEach(btn => {
            btn.addEventListener('click', function() {
                const id = this.getAttribute('data-id');
                const title = this.getAttribute('data-title') || '';
                deleteForm.action = deleteForm.action.replace('/0', `/${id}`);
                deleteTitle.textContent = title;
                bsDeleteModal.show();
            });
        });

        deleteModalEl.addEventListener('hidden.bs.modal', function() {
            deleteForm.action = `{{ route('admin.materials.destroy', 0) }}`;
        });
    }
});
</script>
@endpush

@endsection
