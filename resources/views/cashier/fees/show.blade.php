@extends('layouts.app')

@section('title', 'Review Payment — ' . $fee->fee_type)

@section('content')
<h2 class="mb-4"><i class="bi bi-currency-exchange me-2"></i>Review Payment</h2>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Fee Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Student</dt><dd class="col-sm-8 fw-medium">{{ $fee->student->user->name ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Student No.</dt><dd class="col-sm-8">{{ $fee->student->student_number ?? '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Fee Type</dt><dd class="col-sm-8">{{ $fee->fee_type }}</dd>
                    <dt class="col-sm-4 text-muted">Amount</dt><dd class="col-sm-8 fw-bold fs-5 text-primary">₱{{ number_format($fee->amount, 2) }}</dd>
                    <dt class="col-sm-4 text-muted">Due Date</dt><dd class="col-sm-8">{{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('M j, Y') : '—' }}</dd>
                    <dt class="col-sm-4 text-muted">Status</dt><dd class="col-sm-8"><span class="badge bg-{{ $fee->status === 'paid' ? 'success' : 'warning' }}">{{ ucfirst($fee->status) }}</span></dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Payment Information</div>
            <div class="card-body">
                @if($fee->payment_method)
                    <dl class="row mb-0">
                        <dt class="col-sm-4 text-muted">Payment Method</dt><dd class="col-sm-8"><span class="badge bg-info">{{ ucfirst(str_replace('_', ' ', $fee->payment_method)) }}</span></dd>
                        <dt class="col-sm-4 text-muted">Reference</dt><dd class="col-sm-8">{{ $fee->payment_reference ?? '—' }}</dd>
                        <dt class="col-sm-4 text-muted">Payment Status</dt><dd class="col-sm-8">
                            @if($fee->payment_status === 'pending')
                                <span class="badge bg-warning">Pending verification</span>
                            @elseif($fee->payment_status === 'verified')
                                <span class="badge bg-success">Verified</span>
                            @elseif($fee->payment_status === 'rejected')
                                <span class="badge bg-danger">Rejected</span>
                            @else
                                <span class="text-muted">—</span>
                            @endif
                        </dd>
                        @if($fee->payment_proof_path)
                            <dt class="col-sm-4 text-muted">Proof</dt><dd class="col-sm-8">
                                <a href="{{ asset($fee->payment_proof_path) }}" target="_blank" class="btn btn-sm btn-outline-primary">
                                    <i class="bi bi-file-earmark-image me-1"></i>View proof
                                </a>
                            </dd>
                        @endif
                    </dl>
                @else
                    <p class="text-muted mb-0">No payment information submitted yet.</p>
                @endif
            </div>
        </div>
    </div>
</div>

@if($fee->payment_status === 'pending' && $fee->payment_method)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-transparent fw-semibold">Verify Payment</div>
        <div class="card-body">
            <p class="small text-muted mb-3">Review the payment details and proof (if provided). Verify if payment is correct, or reject if there are issues.</p>
            <form action="{{ route('cashier.fees.verify-payment', $fee) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="action" value="verify">
                <button type="submit" class="btn btn-success me-2">
                    <i class="bi bi-check-circle me-1"></i>Verify & Mark Paid
                </button>
            </form>
            <form action="{{ route('cashier.fees.verify-payment', $fee) }}" method="post" class="d-inline">
                @csrf
                <input type="hidden" name="action" value="reject">
                <button type="submit" class="btn btn-danger" onclick="return confirm('Reject this payment? Student will need to resubmit.')">
                    <i class="bi bi-x-circle me-1"></i>Reject Payment
                </button>
            </form>
        </div>
    </div>
@elseif($fee->status === 'pending' && !$fee->payment_method)
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form action="{{ route('cashier.fees.mark-paid', $fee) }}" method="post">
                @csrf
                <p class="small text-muted mb-3">No online payment submitted. Mark as paid if student paid over-the-counter.</p>
                <button type="submit" class="btn btn-success">Mark as Paid (Cash/OTC)</button>
            </form>
        </div>
    </div>
@endif

<p class="small text-muted mt-2 mb-0"><a href="{{ route('cashier.fees.index') }}">&larr; Back to fees</a></p>
@endsection
