@extends('layouts.app')

@section('title', 'Student — ' . ($student->user->name ?? $student->student_number))

@section('content')
<h2 class="mb-4"><i class="bi bi-person-badge me-2"></i>Student (view only)</h2>
<p class="text-muted small mb-3">View-only. No editing.</p>
<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Profile</div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-sm-4 text-muted">Student No.</dt><dd class="col-sm-8">{{ $student->student_number }}</dd>
                    <dt class="col-sm-4 text-muted">Name</dt><dd class="col-sm-8">{{ $student->user->name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Email</dt><dd class="col-sm-8">{{ $student->user->email ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Program</dt><dd class="col-sm-8">{{ $student->program ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Year level</dt><dd class="col-sm-8">{{ $student->year_level ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($student->status) }}</span></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Fees (for clearance)</div>
            <ul class="list-group list-group-flush">
                @forelse($student->fees as $f)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        {{ $f->fee_type }} — ₱{{ number_format($f->amount, 2) }}
                        <span class="badge bg-{{ $f->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($f->status) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No fees.</li>
                @endforelse
            </ul>
        </div>
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Enrollments</div>
            <ul class="list-group list-group-flush">
                @forelse($student->enrollments as $e)
                    <li class="list-group-item">{{ $e->course->code ?? '—' }} — {{ $e->course->title ?? '—' }}</li>
                @empty
                    <li class="list-group-item text-muted">No enrollments.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<p class="small text-muted mb-0"><a href="{{ route('cashier.students.index') }}">&larr; Back to students</a></p>
@endsection
