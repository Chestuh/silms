@extends('layouts.app')

@section('title', 'Credential Payment Clearance')

@section('content')
<h2 class="mb-4"><i class="bi bi-file-earmark-text me-2"></i>Credential payment clearance</h2>
<p class="text-muted small mb-3">Confirm payment cleared so admin can release credentials.</p>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Student No.</th>
                        <th>Credential type</th>
                        <th>Status</th>
                        <th>Payment cleared</th>
                        <th>Requested</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($requests as $r)
                        <tr>
                            <td class="fw-medium">{{ $r->student->user->name ?? '—' }}</td>
                            <td>{{ $r->student->student_number ?? '—' }}</td>
                            <td>{{ $r->credential_type }}</td>
                            <td><span class="badge bg-{{ $r->status === 'ready' ? 'success' : ($r->status === 'processing' ? 'info' : 'warning') }}">{{ ucfirst($r->status) }}</span></td>
                            <td>
                                @if($r->payment_cleared_at)
                                    <span class="text-success small"><i class="bi bi-check-circle me-1"></i>{{ \Carbon\Carbon::parse($r->payment_cleared_at)->format('M j, Y') }}</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ \Carbon\Carbon::parse($r->created_at)->format('M j, Y') }}</td>
                            <td>
                                @if(!$r->payment_cleared_at && in_array($r->status, ['pending', 'processing']))
                                    <form action="{{ route('cashier.credentials.clear-payment', $r) }}" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Clear payment</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No credential requests.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($requests->hasPages())
        <div class="card-footer">{{ $requests->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('cashier.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
