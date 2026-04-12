@extends('layouts.app')

@section('title', 'Disciplinary Records')

@section('content')
<h2 class="mb-4"><i class="bi bi-shield-exclamation me-2"></i>Disciplinary Records</h2>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Incident Date</th><th>Description</th><th>Sanction</th><th>Status</th></tr></thead>
            <tbody>
                @foreach($records as $r)
                <tr>
                    <td>{{ $r->incident_date ? \Carbon\Carbon::parse($r->incident_date)->format('M j, Y') : '—' }}</td>
                    <td>{{ $r->description ?? '—' }}</td>
                    <td>{{ $r->sanction ?? '—' }}</td>
                    <td><span class="badge bg-{{ $r->status === 'resolved' ? 'success' : ($r->status === 'appealed' ? 'warning' : 'secondary') }}">{{ ucfirst($r->status) }}</span></td>
                </tr>
                @endforeach
                @if($records->isEmpty())
                <tr><td colspan="4" class="text-center text-muted">No disciplinary records.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
