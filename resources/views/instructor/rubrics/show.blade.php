@extends('layouts.app')

@section('title', $rubric->name)

@section('content')
<div class="d-flex align-items-center justify-content-between mb-4">
    <div>
        <h2 class="mb-0"><i class="bi bi-eye me-2"></i>{{ $rubric->name }}</h2>
        <p class="text-muted small mb-0">Rubric for {{ $rubric->course->code ?? 'Unknown course' }}{{ $rubric->material ? ' — ' . $rubric->material->title : '' }}.</p>
    </div>
    <div class="btn-group">
        <a href="{{ route('instructor.rubrics.edit', $rubric) }}" class="btn btn-outline-secondary">Edit</a>
        <form action="{{ route('instructor.rubrics.destroy', $rubric) }}" method="POST" class="d-inline">
            @csrf
            @method('DELETE')
            <button type="submit" class="btn btn-outline-danger" onclick="return confirm('Delete this rubric?');">Delete</button>
        </form>
    </div>
</div>
<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <dl class="row mb-0">
            <dt class="col-sm-3">Course</dt>
            <dd class="col-sm-9">{{ $rubric->course->code ?? '—' }} — {{ $rubric->course->title ?? '—' }}</dd>
            <dt class="col-sm-3">Material</dt>
            <dd class="col-sm-9">{{ $rubric->material->title ?? 'Course-level rubric' }}</dd>
            <dt class="col-sm-3">Last updated</dt>
            <dd class="col-sm-9">{{ \Carbon\Carbon::parse($rubric->updated_at)->format('M j, Y') }}</dd>
        </dl>
    </div>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <h5 class="mb-3">Criteria</h5>
        <div class="table-responsive">
            <table class="table table-bordered align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>#</th>
                        <th>Description</th>
                        <th style="width:140px;">Weight (%)</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rubric->criteria_json ?? [] as $criterion)
                        <tr>
                            <td>{{ $loop->iteration }}</td>
                            <td>{{ $criterion['description'] ?? '—' }}</td>
                            <td>{{ $criterion['weight'] ?? '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3" class="text-center text-muted py-4">No criteria defined.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.rubrics.index') }}">&larr; Back to rubrics</a></p>
@endsection