@extends('layouts.app')

@section('title', 'Self-Assessment')

@section('content')
<h2 class="mb-4"><i class="bi bi-clipboard-check me-2"></i>Student Self-Assessment Checker</h2>
<div class="card">
    <div class="card-body">
        <p class="text-muted mb-0">Self-assessment forms may be assigned by your instructor. Completed assessments and scores are listed below.</p>
    </div>
</div>
<div class="card mt-4">
    <div class="card-header">My self-assessments</div>
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Title</th><th>Course</th><th>Score</th><th>Completed</th></tr></thead>
            <tbody>
                @foreach($assessments as $a)
                <tr>
                    <td>{{ $a->title ?? '—' }}</td>
                    <td>{{ optional($a->course)->code ?? '—' }}</td>
                    <td>{{ $a->score !== null ? $a->score : '—' }}</td>
                    <td>{{ $a->created_at ? \Carbon\Carbon::parse($a->created_at)->format('M j, Y') : '—' }}</td>
                </tr>
                @endforeach
                @if($assessments->isEmpty())
                <tr><td colspan="4" class="text-center text-muted">No self-assessments yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
