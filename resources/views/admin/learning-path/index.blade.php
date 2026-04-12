@extends('layouts.app')

@section('title', 'Learning Path Configuration')

@section('content')
<h2 class="mb-4"><i class="bi bi-signpost-2 me-2"></i>Learning Path Guidance</h2>
<p class="text-muted small mb-3">Configure rules and templates for guided learning sequences. The system suggests recommended learning paths based on student performance, progress, and learning goals.</p>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-body">
        <h6 class="fw-semibold mb-2">Current behavior</h6>
        <p class="small text-muted mb-2">Recommendation is {{ $recommendationEnabled ? 'enabled' : 'disabled' }} (controlled via config).</p>
        <p class="small mb-0">Learning path recommendations use course–material mapping, completion status, and the rules below. You can add prerequisite chains (e.g. complete Course A before Course B) and enable difficulty-based ordering (easy → medium → hard).</p>
    </div>
</div>

<div class="card border-0 shadow-sm mb-4">
    <div class="card-header bg-transparent fw-semibold d-flex align-items-center justify-content-between flex-wrap gap-2">
        <span>Rules &amp; templates</span>
        <a href="{{ route('admin.learning-path.create') }}" class="btn btn-sm btn-primary"><i class="bi bi-plus-lg me-1"></i>Add rule</a>
    </div>
    <div class="card-body p-0">
        @if($rules->isEmpty())
            <p class="small text-muted p-3 mb-0">No rules yet. Add a rule to enforce prerequisites or difficulty ordering.</p>
        @else
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Type</th>
                            <th>Rule</th>
                            <th>Order</th>
                            <th>Active</th>
                            <th></th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($rules as $r)
                            <tr>
                                <td>
                                    @if($r->type === 'course_prerequisite')
                                        <span class="badge bg-primary">Course prerequisite</span>
                                    @elseif($r->type === 'material_prerequisite')
                                        <span class="badge bg-info">Material prerequisite</span>
                                    @else
                                        <span class="badge bg-secondary">Difficulty order</span>
                                    @endif
                                </td>
                                <td>
                                    @if($r->name)
                                        {{ $r->name }}
                                    @elseif($r->type === 'course_prerequisite')
                                        {{ $r->sourceCourse->code ?? '—' }} → {{ $r->targetCourse->code ?? '—' }}
                                    @elseif($r->type === 'material_prerequisite')
                                        {{ $r->sourceMaterial->title ?? '—' }} → {{ $r->targetMaterial->title ?? '—' }}
                                    @else
                                        Easy → Medium → Hard
                                    @endif
                                </td>
                                <td>{{ $r->sort_order }}</td>
                                <td>
                                    @if($r->is_active)
                                        <span class="badge bg-success">Yes</span>
                                    @else
                                        <span class="badge bg-secondary">No</span>
                                    @endif
                                </td>
                                <td>
                                    <a href="{{ route('admin.learning-path.edit', $r) }}" class="btn btn-sm btn-outline-primary">Edit</a>
                                    <form method="POST" action="{{ route('admin.learning-path.destroy', $r) }}" class="d-inline" onsubmit="return confirm('Delete this rule?');">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </div>
</div>

<p class="small text-muted mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
