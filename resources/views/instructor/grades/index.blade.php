@extends('layouts.app')

@section('title', 'Grades')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-check me-2"></i>Grades</h2>
<p class="text-muted small mb-3">Encode or update midterm and final grades for your classes.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th class="text-center">Units</th>
                        <th class="text-center">Enrolled</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="fw-medium">{{ $course->code }}</td>
                            <td>{{ $course->title }}</td>
                            <td class="text-center">{{ $course->units }}</td>
                            <td class="text-center">{{ $course->enrollments_count }}</td>
                            <td>
                                <a href="{{ route('instructor.grades.show', $course) }}" class="btn btn-sm btn-primary">Encode grades</a>
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No courses assigned. Grades are available per course.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
