@extends('layouts.app')

@section('title', 'Enrollment details')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Enrollment details</h2>
<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Enrollee</div>
            <div class="card-body">
                <dl class="row small mb-0">
                    <dt class="col-sm-4 text-muted">Student</dt><dd class="col-sm-8">{{ $enrollment->student->user->name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Student No.</dt><dd class="col-sm-8">{{ $enrollment->student->student_number ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Course</dt><dd class="col-sm-8">{{ $enrollment->course->code ?? '—' }} — {{ $enrollment->course->title ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Semester</dt><dd class="col-sm-8">{{ $enrollment->semester ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">School year</dt><dd class="col-sm-8">{{ $enrollment->school_year ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $enrollment->status === 'enrolled' ? 'success' : 'secondary' }}">{{ ucfirst($enrollment->status) }}</span></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Attachments</div>
            <div class="card-body">
                @php $attachments = $enrollment->attachments ?? []; @endphp
                @if(!empty($attachments))
                    <ul class="list-unstyled small mb-0">
                        @foreach($attachments as $label => $path)
                            <li class="mb-2">
                                <strong>{{ ucfirst(str_replace('_', ' ', $label)) }}:</strong>
                                @if($path)
                                    <a href="{{ asset('storage/' . $path) }}" target="_blank">View / Download</a>
                                @else
                                    <span class="text-muted">—</span>
                                @endif
                            </li>
                        @endforeach
                    </ul>
                    <p class="small text-muted mt-2 mb-0">School requirements, report cards, good moral, form 137, etc. can be stored here when the upload feature is added.</p>
                @else
                    <p class="text-muted small mb-0">No attachments yet. Attachments (school requirements, report cards, good moral, form 137) can be linked here when the upload feature is extended.</p>
                @endif
            </div>
        </div>
    </div>
</div>
<div class="mb-3">
    @if($enrollment->status !== 'enrolled')
        <form method="POST" action="{{ route('admin.enrollments.approve', $enrollment) }}" class="d-inline">
            @csrf
            <button type="submit" class="btn btn-success">Approve enrollment</button>
        </form>
    @endif
    @if($enrollment->status !== 'dropped')
        <form method="POST" action="{{ route('admin.enrollments.reject', $enrollment) }}" class="d-inline" onsubmit="return confirm('Reject this enrollment?');">
            @csrf
            <button type="submit" class="btn btn-outline-danger">Reject</button>
        </form>
    @endif
</div>
<p class="small text-muted mb-0"><a href="{{ route('admin.enrollments.index') }}">&larr; Back to enrollments</a></p>
@endsection
