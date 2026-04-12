@extends('layouts.app')

@section('title', 'Fees & Payment Review')

@section('content')
<div class="d-flex flex-wrap justify-content-between align-items-center gap-2 mb-4">
    <div>
        <h2 class="mb-1"><i class="bi bi-currency-exchange me-2"></i>Fees & payment review</h2>
        <p class="text-muted small mb-0">Review and mark fees as paid.</p>
    </div>
    <a href="{{ route('cashier.fees.create') }}" class="btn btn-primary"><i class="bi bi-plus-circle me-1"></i>Add payment to all students</a>
</div>

<form method="get" class="mb-3">
    <div class="row g-2 align-items-center">
        <div class="col-auto">
            <label class="form-label small mb-0">Status</label>
            <select name="status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="pending" {{ request('status') === 'pending' ? 'selected' : '' }}>Pending</option>
                <option value="paid" {{ request('status') === 'paid' ? 'selected' : '' }}>Paid</option>
            </select>
        </div>
        <div class="col-auto">
            <label class="form-label small mb-0">Payment Status</label>
            <select name="payment_status" class="form-select form-select-sm" onchange="this.form.submit()">
                <option value="">All</option>
                <option value="pending" {{ request('payment_status') === 'pending' ? 'selected' : '' }}>Pending verification</option>
                <option value="verified" {{ request('payment_status') === 'verified' ? 'selected' : '' }}>Verified</option>
                <option value="rejected" {{ request('payment_status') === 'rejected' ? 'selected' : '' }}>Rejected</option>
            </select>
        </div>
    </div>
</form>

<div class="card border-0 shadow-sm">
    <div class="card-body p-0">
        <div class="table-responsive">
            <table class="table table-hover align-middle mb-0">
                <thead class="table-light">
                    <tr>
                        <th>Student</th>
                        <th>Student No.</th>
                        <th>Fee type</th>
                        <th>Amount</th>
                        <th>Payment Method</th>
                        <th>Status</th>
                        <th>Payment Status</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($fees as $fee)
                        <tr>
                            <td class="fw-medium">{{ $fee->student->user->name ?? '—' }}</td>
                            <td>{{ $fee->student->student_number ?? '—' }}</td>
                            <td>{{ $fee->fee_type }}</td>
                            <td>₱{{ number_format($fee->amount, 2) }}</td>
                            <td>
                                @if($fee->payment_method)
                                    <span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $fee->payment_method)) }}</span>
                                    @if($fee->payment_reference)
                                        <div class="small text-muted mt-1">{{ Str::limit($fee->payment_reference, 15) }}</div>
                                    @endif
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td><span class="badge bg-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($fee->status) }}</span></td>
                            <td>
                                @if($fee->payment_status === 'pending')
                                    <span class="badge bg-warning">Pending Verification</span>
                                @elseif($fee->payment_status === 'verified')
                                    <span class="badge bg-success">Verified</span>
                                @elseif($fee->payment_status === 'rejected')
                                    <span class="badge bg-danger">Rejected</span>
                                @else
                                    <span class="text-muted small">—</span>
                                @endif
                            </td>
                            <td>
                                @if($fee->payment_status === 'pending' || $fee->status === 'pending')
                                    <a href="{{ route('cashier.fees.show', $fee) }}" class="btn btn-sm btn-primary">Review</a>
                                @elseif($fee->status === 'pending')
                                    <form action="{{ route('cashier.fees.mark-paid', $fee) }}" method="post" class="d-inline">
                                        @csrf
                                        <button type="submit" class="btn btn-sm btn-success">Mark paid</button>
                                    </form>
                                @endif
                            </td>
                        </tr>
                    @empty
                        <tr><td colspan="8" class="text-center text-muted py-4">No fees found.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </div>
    @if($fees->hasPages())
        <div class="card-footer">{{ $fees->links() }}</div>
    @endif
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('cashier.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
