@extends('layouts.app')

@section('title', 'GWA & Grades')

@section('content')
<h2 class="mb-4"><i class="bi bi-calculator me-2"></i>GWA & Academic Records</h2>
<div class="card mb-4">
    <div class="card-body">
        <h5>Grade Weighted Average (GWA)</h5>
        <p class="display-6 mb-0">{{ $gwa !== null ? number_format($gwa, 2) : '—' }}</p>
        @php
            if ($gwa === null) {
                $gwaStatus = 'Dropped';
            } elseif ((float) $gwa === 0.0) {
                $gwaStatus = 'INC';
            } elseif ($gwa < 75) {
                $gwaStatus = 'Failed';
            } else {
                $gwaStatus = 'Passed';
            }

            $statusBadge = [
                'Passed' => 'success',
                'Failed' => 'danger',
                'Dropped' => 'secondary',
                'INC' => 'info',
            ][$gwaStatus] ?? 'secondary';
        @endphp
        <!-- <p class="mb-1"><strong>Status:</strong> <span class="badge bg-{{ $statusBadge }}">{{ $gwaStatus }}</span></p> -->
        <div class="mt-3">
            <small class="text-muted">Status legend:</small><br>
            <span class="badge bg-success me-2">Passed</span>
            <span class="badge bg-danger me-2">Failed</span>
            <span class="badge bg-secondary me-2">Dropped</span>
            <span class="badge bg-info">INC</span>
        </div>
        <small class="text-muted d-block mt-2">Based on enrolled/completed courses with grades.</small>
    </div>
</div>
<div class="card">
    <div class="card-header">Grades by Course</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Course</th><th>Title</th><th>Units</th><th>Semester</th><th>SY</th><th>Rubric</th><th>Midterm</th><th>Final</th><th>Average</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($grades as $e)
                @php
                    $g = $e->grade;
                    $avg = $g && $g->midterm_grade !== null && $g->final_grade !== null ? ($g->midterm_grade + $g->final_grade) / 2 : null;
                    if ($avg === null) {
                        $status = 'INC';
                    } elseif ($avg >= 75) {
                        $status = 'Passed';
                    } else {
                        $status = 'Failed';
                    }
                    $statusClass = [
                        'Passed' => 'success',
                        'Failed' => 'danger',
                        'INC' => 'secondary',
                    ][$status] ?? 'secondary';
                @endphp
                <tr>
                    <td>{{ optional($e->course)->code ?? '—' }}</td>
                    <td>{{ optional($e->course)->title ?? '—' }}</td>
                    <td>{{ optional($e->course)->units ?? '—' }}</td>
                    <td>{{ $e->semester ?? '—' }}</td>
                    <td>{{ $e->school_year ?? '—' }}</td>
                    <td>{{ optional($g->rubric)->name ?? '—' }}</td>
                    <td>{{ $g && $g->midterm_grade !== null ? number_format($g->midterm_grade, 0) : '—' }}</td>
                    <td>{{ $g && $g->final_grade !== null ? number_format($g->final_grade, 0) : '—' }}</td>
                    <td>{{ $avg !== null ? number_format($avg, 1) : '—' }}</td>
                    <td><span class="badge bg-{{ $statusClass }}{{ $status === 'At-Risk' ? ' text-dark' : '' }}">{{ $status }}</span></td>
                </tr>
                @endforeach
                @if($grades->isEmpty())
                <tr><td colspan="9" class="text-center text-muted">No grade records yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
