@extends('layouts.app')

@section('title', 'Academic Load Validation')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Academic Load Validation</h2>
<p class="text-muted small mb-3">Validate subject loads for irregular or transferee students to avoid overload or conflicts.</p>

<form method="GET" action="{{ route('admin.academic-load.index') }}" class="row g-2 mb-4">
    <div class="col-auto">
        <label class="form-label mb-0">Max units</label>
        <input type="number" name="max_units" class="form-control form-control-sm" value="{{ $maxUnits }}" min="1" max="50" step="0.5" style="width:6rem;">
    </div>
    <div class="col-auto">
        <label class="form-label mb-0">Semester</label>
        <select name="semester" class="form-select form-select-sm" style="width:auto;">
            <option value="">All</option>
            @foreach($semesters as $s)
                <option value="{{ $s }}" {{ $semester === $s ? 'selected' : '' }}>{{ $s }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto">
        <label class="form-label mb-0">School year</label>
        <select name="school_year" class="form-select form-select-sm" style="width:auto;">
            <option value="">All</option>
            @foreach($schoolYears as $y)
                <option value="{{ $y }}" {{ $schoolYear === $y ? 'selected' : '' }}>{{ $y }}</option>
            @endforeach
        </select>
    </div>
    <div class="col-auto d-flex align-items-end">
        <button type="submit" class="btn btn-primary btn-sm">Apply</button>
    </div>
</form>

@if(count($overloads) > 0)
<div class="alert alert-warning">The following students have enrollments exceeding {{ $maxUnits }} units.</div>
<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Total units</th>
                        <th>Max</th>
                        <th>Courses</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($overloads as $o)
                        <tr>
                            <td>{{ $o['student']->user->name ?? '—' }} <small class="text-muted">({{ $o['student']->student_number }})</small></td>
                            <td class="text-danger fw-bold">{{ number_format($o['total_units'], 1) }}</td>
                            <td>{{ number_format($o['max_units'], 1) }}</td>
                            <td class="small">
                                @foreach($o['enrollments'] as $e)
                                    {{ $e->course->code ?? '—' }} ({{ $e->course->units ?? 0 }})@if(!$loop->last), @endif
                                @endforeach
                            </td>
                            <td><a href="{{ route('admin.students.show', $o['student']) }}" class="btn btn-sm btn-outline-primary">View student</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>
@else
<div class="alert alert-success">No overload detected. All enrolled students are within the selected max units ({{ $maxUnits }}).</div>
@endif

<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
