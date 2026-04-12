@extends('layouts.app')

@section('title', 'Admission Records — Payment Verification')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-text me-2"></i>Admission records</h2>
<p class="text-muted small mb-3">View admission records for payment verification.</p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Student No.</th>
                        <th>Record type</th>
                        <th>Date processed</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $rec)
                        <tr>
                            <td class="fw-medium">{{ $rec->student->user->name ?? '—' }}</td>
                            <td>{{ $rec->student->student_number ?? '—' }}</td>
                            <td>{{ $rec->record_type }}</td>
                            <td>{{ $rec->date_processed ? \Carbon\Carbon::parse($rec->date_processed)->format('M j, Y') : '—' }}</td>
                            <td class="small text-muted">{{ Str::limit($rec->notes, 50) }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="5" class="text-center text-muted py-4">No admission records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
        <div class="card-footer">{{ $records->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('cashier.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
