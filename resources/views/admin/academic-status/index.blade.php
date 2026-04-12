@extends('layouts.app')

@section('title', 'Monitor Student Academic Status')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard2-pulse me-2"></i>Monitor Student Academic Status</h2>
<p class="text-muted small mb-3">Track whether students are Passed, Failed, INC, or DROP.</p>

<div class="row g-2 mb-4">
    <div class="col-6 col-md-2">
        <a href="{{ route('admin.academic-status.index', ['status' => 'passed']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 {{ ($filter ?? '') === 'passed' ? 'border-primary border-2' : '' }}">
                <div class="card-body py-3 text-center">
                    <div class="fw-bold fs-4 text-success">{{ $counts['passed'] }}</div>
                    <div class="small text-muted">Passed</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-2">
        <a href="{{ route('admin.academic-status.index', ['status' => 'failed']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 {{ ($filter ?? '') === 'failed' ? 'border-primary border-2' : '' }}">
                <div class="card-body py-3 text-center">
                    <div class="fw-bold fs-4 text-danger">{{ $counts['failed'] }}</div>
                    <div class="small text-muted">Failed</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-2">
        <a href="{{ route('admin.academic-status.index', ['status' => 'inc']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 {{ ($filter ?? '') === 'inc' ? 'border-primary border-2' : '' }}">
                <div class="card-body py-3 text-center">
                    <div class="fw-bold fs-4 text-info">{{ $counts['inc'] }}</div>
                    <div class="small text-muted">INC</div>
                </div>
            </div>
        </a>
    </div>
    <div class="col-6 col-md-2">
        <a href="{{ route('admin.academic-status.index', ['status' => 'drop']) }}" class="text-decoration-none">
            <div class="card border-0 shadow-sm h-100 {{ ($filter ?? '') === 'drop' ? 'border-primary border-2' : '' }}">
                <div class="card-body py-3 text-center">
                    <div class="fw-bold fs-4 text-secondary">{{ $counts['drop'] }}</div>
                    <div class="small text-muted">DROP</div>
                </div>
            </div>
        </a>
    </div>
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
                        <th></th>
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
                            <td><a href="{{ route('admin.students.show', $s) }}" class="btn btn-sm btn-outline-primary">View</a></td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No students match.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@if(!isset($filter) || $filter === '')
    <p class="small text-muted mt-2 mb-0">Showing all students. Use the cards above to filter by status.</p>
@endif
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
