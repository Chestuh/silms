@extends('layouts.app')

@section('title', 'Rubrics & Grading')

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="mb-0"><i class="bi bi-ui-checks me-2"></i>Rubrics & grading</h2>
        <p class="text-muted small mb-0">Build and manage rubrics for your course materials, then assign grades from the instructor gradebook.</p>
    </div>
    <a href="{{ route('instructor.rubrics.create') }}" class="btn btn-success">Create rubric</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead>
                    <tr>
                        <th>Name</th>
                        <th>Course</th>
                        <th>Material</th>
                        <th>Criteria</th>
                        <th>Updated</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rubrics as $r)
                        <tr>
                            <td class="fw-medium">{{ $r->name ?? 'Untitled rubric' }}</td>
                            <td><span class="text-muted small">{{ $r->course->code ?? '—' }}</span></td>
                            <td>{{ $r->material->title ?? 'Course-level rubric' }}</td>
                            <td>{{ count($r->criteria_json ?? []) }} criterion{{ count($r->criteria_json ?? []) === 1 ? '' : 'a' }}</td>
                            <td class="small text-muted">{{ \Carbon\Carbon::parse($r->updated_at)->format('M j, Y') }}</td>
                            <td class="text-end">
                                <a href="{{ route('instructor.rubrics.show', $r) }}" class="btn btn-sm btn-outline-primary">View</a>
                                <a href="{{ route('instructor.rubrics.edit', $r) }}" class="btn btn-sm btn-outline-secondary">Edit</a>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="6" class="text-muted text-center py-4">No rubrics yet. Create a rubric from your course materials to begin grading with defined criteria.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div class="d-flex justify-content-center mt-3">
            {{ $rubrics->links() }}
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
