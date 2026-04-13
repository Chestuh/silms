@extends('layouts.app')

@section('title', 'Courses')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <h2 class="mb-0"><i class="bi bi-journal-richtext me-2"></i>Courses</h2>
    <a href="{{ route('admin.courses.create') }}" class="btn btn-primary"><i class="bi bi-plus-lg me-1"></i>Add course</a>
</div>
<form method="GET" class="row gx-2 gy-2 align-items-center mb-3">
    <div class="col-auto">
        <label for="grade_level" class="form-label visually-hidden">Grade</label>
        <select name="grade_level" id="grade_level" class="form-select" onchange="this.form.submit()">
            <option value=""{{ request('grade_level') ? '' : ' selected' }}>All</option>
            @foreach($gradeLevels as $grade)
                <option value="{{ $grade }}" @selected(request('grade_level') == $grade)>Grade {{ $grade }}</option>
            @endforeach
        </select>
    </div>
</form>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th>Grade</th>
                        <th>Semester</th>
                        <th>Instructor</th>
                        <th class="text-center">Unit</th>
                        <th class="text-center">Materials</th>
                        <th class="text-center">Enrollments</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $c)
                        <tr>
                            <td class="fw-medium">{{ $c->code }}</td>
                            <td>{{ $c->title }}</td>
                            <td>{{ $c->grade_level ? 'Grade '. $c->grade_level : '—' }}</td>
                            <td>{{ $c->semester ?? '—' }}</td>
                                <td>{{ $c->instructor && $c->instructor->user ? $c->instructor->user->name : '-' }}</td>
                            <td class="text-center">
                                @if(in_array($c->grade_level, ['11', '12']))
                                    {{ $c->units }}
                                @else
                                    &mdash;
                                @endif
                            </td>
                            <td class="text-center">{{ $c->learning_materials_count }}</td>
                            <td class="text-center">{{ $c->enrollments_count }}</td>
                            <td>
                                <a href="{{ route('admin.courses.edit', $c) }}" class="btn btn-sm btn-outline-secondary me-1">Edit</a>
                                <form method="POST" action="{{ route('admin.courses.destroy', $c) }}" class="d-inline" onsubmit="return confirm('This will permanently delete the course. Do you want to continue?');">
                                    @csrf
                                    @method('DELETE')
                                    <button type="submit" class="btn btn-sm btn-outline-danger">Delete</button>
                                </form>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="9" class="text-center text-muted py-4">No courses.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    {{-- @if($courses->hasPages())
        <div class="card-footer">{{ $courses->links() }}</div>
    @endif --}}
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
