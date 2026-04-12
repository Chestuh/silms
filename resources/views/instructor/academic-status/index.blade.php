@extends('layouts.app')

@section('title', 'Student Academic Status')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard2-pulse me-2"></i>Student Academic Status</h2>
<p class="text-muted small mb-3">Monitor academic status of students in your courses: Passed, Failed, INC, DROP.</p>
<div class="mb-3 d-flex gap-2 flex-wrap">
    <a href="{{ route('instructor.academic-status.index') }}" class="btn btn-sm {{ $filter ? 'btn-outline-primary' : 'btn-primary' }}">All</a>
    <a href="{{ route('instructor.academic-status.index', ['status' => 'passed']) }}" class="btn btn-sm {{ $filter === 'passed' ? 'btn-primary' : 'btn-outline-primary' }}">Passed</a>
    <a href="{{ route('instructor.academic-status.index', ['status' => 'failed']) }}" class="btn btn-sm {{ $filter === 'failed' ? 'btn-primary' : 'btn-outline-primary' }}">Failed</a>
    <a href="{{ route('instructor.academic-status.index', ['status' => 'inc']) }}" class="btn btn-sm {{ $filter === 'inc' ? 'btn-primary' : 'btn-outline-primary' }}">INC</a>
    <a href="{{ route('instructor.academic-status.index', ['status' => 'drop']) }}" class="btn btn-sm {{ $filter === 'drop' ? 'btn-primary' : 'btn-outline-primary' }}">DROP</a>
</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student No.</th>
                        <th>Name</th>
                        <th>Academic status</th>
                        <th>GWA</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($students as $s)
                        <tr>
                            <td>{{ $s->student_number }}</td>
                            <td>{{ $s->user->name ?? '—' }}</td>
                            <td>
                                @php $st = $s->resolved_academic_status ?? $s->getResolvedAcademicStatus(); @endphp
                                @php
                                    $badgeColor = match ($st) {
                                        'passed' => 'success',
                                        'failed' => 'danger',
                                        'inc' => 'info',
                                        'drop' => 'secondary',
                                        default => 'dark',
                                    };
                                @endphp
                                <span class="badge bg-{{ $badgeColor }}">{{ strtoupper(str_replace('-', ' ', $st)) }}</span>
                            </td>
                            <td>{{ $s->gwa !== null ? number_format($s->gwa, 2) : '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No students match.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
