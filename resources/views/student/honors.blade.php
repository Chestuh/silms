@extends('layouts.app')

@section('title', 'Academic Honors')

@section('content')
<h2 class="mb-4"><i class="bi bi-trophy me-2"></i>Academic Honors</h2>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Honor Type</th><th>Semester</th><th>School Year</th></tr></thead>
            <tbody>
                @foreach($honors as $h)
                <tr>
                    <td>{{ $h->honor_type }}</td>
                    <td>{{ $h->semester ?? '—' }}</td>
                    <td>{{ $h->school_year ?? '—' }}</td>
                </tr>
                @endforeach
                @if($honors->isEmpty())
                <tr><td colspan="3" class="text-center text-muted">No academic honors recorded yet.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
