@extends('layouts.app')

@section('title', 'Manage Student Admission Records')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-plus me-2"></i>Manage Student Admission Records</h2>
<p class="text-muted small mb-3">Application, admission, enrollment, transfer, leave, and re-admission history.</p>

<div class="mb-3 d-flex flex-wrap gap-2 align-items-center">
    <a href="{{ route('admin.admission.index') }}" class="btn btn-sm {{ $type ? 'btn-outline-primary' : 'btn-primary' }}">All</a>
    <a href="{{ route('admin.admission.index', ['type' => 'admission']) }}" class="btn btn-sm {{ $type === 'admission' ? 'btn-primary' : 'btn-outline-primary' }}">Admission</a>
    <a href="{{ route('admin.admission.index', ['type' => 'transfer']) }}" class="btn btn-sm {{ $type === 'transfer' ? 'btn-primary' : 'btn-outline-primary' }}">Transfer</a>
    <a href="{{ route('admin.admission.index', ['type' => 'readmission']) }}" class="btn btn-sm {{ $type === 'readmission' ? 'btn-primary' : 'btn-outline-primary' }}">Re-admission</a>
    <a href="{{ route('admin.admission.index', ['type' => 'leave']) }}" class="btn btn-sm {{ $type === 'leave' ? 'btn-primary' : 'btn-outline-primary' }}">Leave</a>
    <a href="{{ route('admin.admission.create') }}" class="btn btn-success ms-auto">Add record</a>
</div>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Date</th>
                        <th>Student</th>
                        <th>Type</th>
                        <th>Notes</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($records as $r)
                        <tr>
                            <td>{{ ($r->date_processed ?? $r->created_at) ? \Carbon\Carbon::parse($r->date_processed ?? $r->created_at)->format('M j, Y') : '—' }}</td>
                            <td>{{ $r->student->user->name ?? '—' }} <small class="text-muted">({{ $r->student->student_number }})</small></td>
                            <td><span class="badge bg-secondary">{{ $r->record_type }}</span></td>
                            <td class="small">{{ Str::limit($r->notes, 60) ?: '—' }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4" class="text-center text-muted py-4">No admission records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($records->hasPages())
        <div class="card-footer">{{ $records->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
