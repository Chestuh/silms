@extends('layouts.app')

@section('title', 'Students GWA')

@section('content')
<h2 class="mb-4"><i class="bi bi-calculator me-2"></i>Students GWA</h2>
<p class="text-muted small mb-3">General Weighted Average for students enrolled in your courses.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>GWA</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td>{{ $s->student_number }}</td>
                            <td>{{ $s->user->name ?? '—' }}</td>
                            <td>{{ $s->gwa !== null ? number_format($s->gwa, 2) : '—' }}</td>
                            <td><a href="{{ route('instructor.grades.index') }}" class="btn btn-sm btn-outline-primary">Grades</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No students in your courses.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
