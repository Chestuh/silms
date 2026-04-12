@extends('layouts.app')

@section('title', 'Admission Records')

@section('content')
<h2 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i>Admission Records</h2>
<p class="text-muted">Transfer and re-admission handling records.</p>
<div class="card">
    <div class="table-responsive">
        <table class="table table-hover mb-0">
            <thead><tr><th>Type</th><th>Date Processed</th><th>Notes</th></tr></thead>
            <tbody>
                @foreach($records as $r)
                <tr>
                    <td><span class="badge bg-info">{{ $r->record_type }}</span></td>
                    <td>{{ $r->date_processed ? \Carbon\Carbon::parse($r->date_processed)->format('M j, Y') : '—' }}</td>
                    <td>{{ $r->notes ?? '—' }}</td>
                </tr>
                @endforeach
                @if($records->isEmpty())
                <tr><td colspan="3" class="text-center text-muted">No admission/transfer/re-admission records.</td></tr>
                @endif
            </tbody>
        </table>
    </div>
</div>
@endsection
