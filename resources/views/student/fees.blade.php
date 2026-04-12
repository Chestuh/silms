@extends('layouts.app')

@section('title', 'Fees & Payments')

@section('content')
<h2 class="mb-4"><i class="bi bi-credit-card me-2"></i>Fees & Payments</h2>
<p class="text-muted small mb-3">View your fees and make payments via GCash, cash, or bank transfer.</p>

@if(session('info'))
    <div class="alert alert-info alert-dismissible fade show">{{ session('info') }}
        <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
    </div>
@endif

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Fee Type</th>
                        <th>Amount</th>
                        <th>Due Date</th>
                        <th>Status</th>
                        <th>Payment</th>
                        <th>Paid At</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $f)
                        <tr>
                            <td class="fw-medium">{{ $f->fee_type }}</td>
                            <td>₱{{ number_format($f->amount, 2) }}</td>
                            <td>{{ $f->due_date ? \Carbon\Carbon::parse($f->due_date)->format('M j, Y') : '—' }}</td>
                            <td>
                                <span class="badge bg-{{ $f->status === 'paid' ? 'success' : ($f->status === 'overdue' ? 'danger' : 'warning') }}">
                                    {{ ucfirst($f->status) }}
                                </span>
                            </td>
                            <td>
                                @if($f->payment_method)
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $f->payment_method)) }}</span>
                                    @if($f->payment_status === 'pending')
                                        <span class="badge bg-warning ms-1">Pending Verification</span>
                                    @elseif($f->payment_status === 'verified')
                                        <span class="badge bg-success ms-1">Verified</span>
                                    @elseif($f->payment_status === 'rejected')
                                        <span class="badge bg-danger ms-1">Rejected</span>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>{{ $f->paid_at ? \Carbon\Carbon::parse($f->paid_at)->format('M j, Y g:i A') : '—' }}</td>
                            <td>
                                @if($f->status === 'pending')
                                    <a href="{{ route('student.fees.pay', $f) }}" class="btn btn-sm btn-primary">Pay Now</a>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="7" class="text-center text-muted py-4">No fee records.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
@endsection
