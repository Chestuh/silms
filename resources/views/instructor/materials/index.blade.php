@extends('layouts.app')

@section('title', 'Learning Materials')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-folder2-open me-2"></i>{{ ($activeTab ?? 'all') === 'archived' ? 'Archived materials' : 'Learning materials' }}</h2>
        <div class="btn-group" role="group" aria-label="Materials tab navigation">
            <a href="{{ route('instructor.materials.index', ['tab' => 'all']) }}" class="btn btn-outline-primary {{ ($activeTab ?? 'all') === 'all' ? 'active' : '' }}">Materials</a>
            <a href="{{ route('instructor.materials.index', ['tab' => 'archived']) }}" class="btn btn-outline-primary {{ ($activeTab ?? '') === 'archived' ? 'active' : '' }}">Archived</a>
        </div>
    </div>
    @if(($activeTab ?? 'all') !== 'archived')
        <a href="{{ route('instructor.materials.create') }}" class="btn btn-success"><i class="bi bi-plus-lg me-1"></i>Add learning material</a>
    @endif
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
                            <td class="fw-medium">
                                {{ $m->title }}
                                @if($m->archived)
                                    <span class="badge bg-secondary ms-2">Archived</span>
                                @endif
                            </td>
                            <td>{{ $m->format ?? '—' }}</td>
                            <td class="text-end">
                                <a href="{{ route('instructor.materials.edit', $m) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                                @if($m->archived)
                                    <form action="{{ route('instructor.materials.unarchive', $m) }}" method="POST" class="d-inline-block me-1" onsubmit="return confirm('Restore this learning material from archive?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-success">Restore</button>
                                    </form>
                                @else
                                    <form action="{{ route('instructor.materials.archive', $m) }}" method="POST" class="d-inline-block me-1" onsubmit="return confirm('Archive this learning material?');">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-outline-warning">Archive</button>
                                    </form>
                                @endif
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
                            <td colspan="4" class="text-muted text-center py-4">
                                @if(($activeTab ?? 'all') === 'archived')
                                    No archived learning materials yet. <a href="{{ route('instructor.materials.index', ['tab' => 'all']) }}">View active materials</a>.
                                @else
                                    No learning materials yet. <a href="{{ route('instructor.materials.create') }}">Add one</a>.
                                @endif
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
