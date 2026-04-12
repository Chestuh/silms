@extends('layouts.app')

@section('title', 'Manage Courses')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-richtext me-2"></i>Manage courses</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <div class="table-responsive">
            <table class="table table-hover align-middle">
                <thead>
                    <tr>
                        <th>Code</th>
                        <th>Title</th>
                        <th class="text-center">Materials</th>
                        <th class="text-center">Enrollments</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($courses as $course)
                        <tr>
                            <td class="fw-medium">{{ $course->code }}</td>
                            <td>{{ $course->title }}</td>
                            <td class="text-center">{{ $course->learning_materials_count }}</td>
                            <td class="text-center">{{ $course->enrollments_count }}</td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="4" class="text-muted text-center py-4">No courses assigned yet.</td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
