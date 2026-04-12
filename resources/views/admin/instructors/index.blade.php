@extends('layouts.app')

@section('title', 'Instructors')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h2 class="mb-0"><i class="bi bi-person-gear me-2"></i>Instructors</h2>
    <div class="d-flex gap-2">
        <select class="form-select" id="departmentFilter" style="max-width: 200px;">
            <option value="">-- All Departments --</option>
            @foreach($departments as $d)
                <option value="{{ $d }}" {{ $department === $d ? 'selected' : '' }}>Grade {{ $d }}</option>
            @endforeach
        </select>
        <a href="{{ route('admin.instructors.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add instructor</a>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Name</th>
                        <th>Email</th>
                        <th>Department</th>
                        <th class="text-center">Courses</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($instructors as $i)
                        <tr>
                            <td class="fw-medium">{{ $i->user->name ?? '—' }}</td>
                            <td>{{ $i->user->email ?? '—' }}</td>
                            <td>{{ $i->department ? 'Grade ' . $i->department . ' Department' : '—' }}</td>
                            <td class="text-center">{{ $i->courses_count }}</td>
                            <td class="text-end">
                                <a href="{{ route('admin.instructors.edit', $i) }}" class="btn btn-sm btn-outline-secondary me-2">Edit / Assign courses</a>
                                <form method="POST" action="{{ route('admin.instructors.destroy', $i) }}" style="display:inline" onsubmit="return confirm('Delete this instructor? This will remove their account and unassign their courses. Continue?')">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No instructors.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($instructors->hasPages())
        <div class="card-footer">{{ $instructors->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>

@push('scripts')
<script>
document.getElementById('departmentFilter').addEventListener('change', function() {
    const department = this.value;
    const url = department ? `{{ route('admin.instructors.index') }}?department=${department}` : `{{ route('admin.instructors.index') }}`;
    window.location.href = url;
});
</script>
@endpush
@endsection
