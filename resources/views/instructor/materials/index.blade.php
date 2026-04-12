@extends('layouts.app')

@section('title', 'Learning Materials')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h2 class="mb-0"><i class="bi bi-folder2-open me-2"></i>Learning materials</h2>
    <a href="{{ route('instructor.materials.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Add learning material</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Course</th>
                        <th>Title</th>
                        <th>Format</th>
                        <th class="text-end">Actions</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($materials as $m)
                        <tr>
                            <td><span class="text-muted small">{{ $m->course->code ?? '—' }}</span></td>
                            <td class="fw-medium">{{ $m->title }}</td>
                            <td>{{ $m->format ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('instructor.materials.edit', $m) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                                <form action="{{ route('instructor.materials.destroy', $m) }}" method="POST" class="d-inline-block" onsubmit="return confirm('Remove this material?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Remove</button>
                                </form>
                                @if($m->url)
                                    <a href="{{ $m->url }}" target="_blank" class="btn btn-sm btn-outline-primary ms-1">Open</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center py-4">No learning materials yet. <a href="{{ route('instructor.materials.create') }}">Add one</a>.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
