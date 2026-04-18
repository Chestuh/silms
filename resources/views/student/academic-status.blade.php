@extends('layouts.app')

@section('title', 'Academic Status')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Academic Status</h2>
<div class="card mb-3">
    <div class="card-body">
        <p class="mb-0"><strong>Overall status:</strong> <span class="badge bg-success">{{ ucfirst($student->status) }}</span></p>
    </div>
</div>
<div class="card">
    <div class="card-header">Enrollment & Academic Load</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Course</th><th>Title</th><th>Units</th><th>Semester</th><th>School Year</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($enrollments as $e)
                <tr>
                    <td>{{ optional($e->course)->code ?? '—' }}</td>
                    <td>{{ optional($e->course)->title ?? '—' }}</td>
                    <td>{{ optional($e->course)->units ?? '—' }}</td>
                    <td>{{ $e->semester ?? '—' }}</td>
                    <td>{{ $e->school_year ?? '—' }}</td>
                    <td><span class="badge bg-{{ $e->status === 'enrolled' ? 'primary' : ($e->status === 'completed' ? 'success' : 'secondary') }}">{{ ucfirst($e->status) }}</span></td>
                </tr>
                @endforeach
                @if($enrollments->isEmpty())
                <tr><td colspan="6" class="text-center text-muted">No enrollment records.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
    @if($totalUnits > 0)
    <div class="card-footer"><strong>Current load (enrolled):</strong> {{ number_format($totalUnits, 1) }} units</div>
    @endif
</div>
@endsection
