@extends('layouts.app')

@section('title', 'Student — ' . ($student->user->name ?? $student->student_number))

@section('content')
<h2 class="mb-4"><i class="bi bi-person-badge me-2"></i>Student information</h2>
<p class="text-muted small mb-3">Full edit rights for profile and academic records.</p>
<div class="mb-3"><a href="{{ route('admin.students.edit', $student) }}" class="btn btn-sm btn-primary">Edit student</a></div>
<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Profile</div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-sm-4 text-muted">Student No.</dt><dd class="col-sm-8">{{ $student->student_number }}</dd>
                    <dt class="col-sm-4 text-muted">Name</dt><dd class="col-sm-8">{{ $student->user->name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Email</dt><dd class="col-sm-8">{{ $student->user->email ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Grade</dt><dd class="col-sm-8">Grade {{ (int)($student->year_level ?? 0) + 6 }}</dd>
                    <dt class="col-sm-4 text-muted">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $student->status === 'active' ? 'success' : 'secondary' }}">{{ ucfirst($student->status) }}</span></dd>
                    <dt class="col-sm-4 text-muted">Academic status</dt><dd class="col-sm-8"><span class="badge bg-{{ $academicStatus === 'failed' ? 'danger' : ($academicStatus === 'inc' ? 'info' : ($academicStatus === 'drop' ? 'secondary' : 'success')) }}">{{ strtoupper($academicStatus) }}</span></dd>
                    <dt class="col-sm-4 text-muted">GWA</dt><dd class="col-sm-8">{{ $gwa !== null ? number_format($gwa, 2) : '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Units</dt><dd class="col-sm-8">{{ number_format($enrolledUnits, 1) }} units</dd>
                    <dt class="col-sm-4 text-muted">Admission date</dt><dd class="col-sm-8">{{ $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('M j, Y') : '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Enrollments & Grades</div>
            <ul class="list-group list-group-flush">
                @forelse($student->enrollments as $e)
                    <li class="list-group-item d-flex justify-content-between align-items-center">
                        <div>
                            {{ $e->course->code ?? '—' }} — {{ $e->course->title ?? '—' }}
                            @if($e->grade && $e->grade->final_grade !== null)
                                <span class="text-muted small ms-2">Final: {{ number_format($e->grade->final_grade, 0) }}</span>
                            @endif
                        </div>
                        <span class="badge bg-{{ $e->status === 'enrolled' ? 'success' : 'secondary' }}">{{ ucfirst($e->status) }}</span>
                    </li>
                @empty
                    <li class="list-group-item text-muted">No enrollments.</li>
                @endforelse
            </ul>
        </div>
    </div>
</div>
<p class="small text-muted mb-0"><a href="{{ route('admin.students.index') }}">&larr; Back to students</a></p>
@endsection
