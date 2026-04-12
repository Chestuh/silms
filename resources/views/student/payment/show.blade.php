@extends('layouts.app')

@section('title', 'Pay Fee — ' . $fee->fee_type)

@section('content')
<h2 class="mb-4"><i class="bi bi-credit-card me-2"></i>Pay Fee</h2>

<div class="row">
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Fee Details</div>
            <div class="card-body">
                <dl class="row mb-0">
                    <dt class="col-sm-4 text-muted">Fee Type</dt><dd class="col-sm-8 fw-medium">{{ $fee->fee_type }}</dd>
                    <dt class="col-sm-4 text-muted">Amount</dt><dd class="col-sm-8 fw-bold fs-5 text-primary">₱{{ number_format($fee->amount, 2) }}</dd>
                    <dt class="col-sm-4 text-muted">Due Date</dt><dd class="col-sm-8">{{ $fee->due_date ? \Carbon\Carbon::parse($fee->due_date)->format('M j, Y') : '—' }}</dd>
                </dl>
            </div>
        </div>
    </div>
    <div class="col-lg-6">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">Payment Methods</div>
            <div class="card-body">
                <form action="{{ route('student.fees.pay.store', $fee) }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Payment Method <span class="text-danger">*</span></label>
                        <select name="payment_method" class="form-select @error('payment_method') is-invalid @enderror" id="paymentMethod" required>
                            <option value="">Select method</option>
                            <option value="gcash" {{ old('payment_method') === 'gcash' ? 'selected' : '' }}>GCash</option>
                            <option value="cash" {{ old('payment_method') === 'cash' ? 'selected' : '' }}>Cash (Over-the-counter)</option>
                            <option value="bank_transfer" {{ old('payment_method') === 'bank_transfer' ? 'selected' : '' }}>Bank Transfer</option>
                        </select>
                        @error('payment_method')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div id="gcashSection" class="mb-3" style="display: none;">
                        <div class="alert alert-info">
                            <h6 class="fw-semibold mb-2"><i class="bi bi-qr-code me-2"></i>GCash Payment</h6>
                            <p class="small mb-2">Scan the QR code below or send payment to:</p>
                            @php
                                $gcashNumber = config('app.gcash_number', '09123456789');
                                $gcashName = config('app.gcash_name', 'CSP Learning Portal');
                                $qrData = "GCash Number: {$gcashNumber}\nAmount: ₱" . number_format($fee->amount, 2);
                            @endphp
                            <div class="bg-white p-3 rounded text-center mb-2">
                                <img src="https://api.qrserver.com/v1/create-qr-code/?size=200x200&data={{ urlencode($qrData) }}" alt="GCash QR Code" class="img-fluid" style="max-width: 200px;">
                                <p class="small mb-0 mt-2"><strong>{{ $gcashNumber }}</strong></p>
                                <p class="small text-muted mb-0">{{ $gcashName }}</p>
                            </div>
                            <p class="small mb-0"><strong>Amount:</strong> ₱{{ number_format($fee->amount, 2) }}</p>
                            <p class="small mb-0 text-muted">After payment, upload your receipt/proof below.</p>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Reference <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('payment_reference') is-invalid @enderror" name="payment_reference" value="{{ old('payment_reference') }}" placeholder="e.g. GCash transaction number, receipt number" required>
                        <div class="form-text">Enter your transaction/reference number from your payment.</div>
                        @error('payment_reference')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Payment Proof (Optional)</label>
                        <input type="file" class="form-control @error('payment_proof') is-invalid @enderror" name="payment_proof" accept="image/*,.pdf">
                        <div class="form-text">Upload screenshot/receipt (JPG, PNG, or PDF, max 5MB).</div>
                        @error('payment_proof')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="d-grid gap-2">
                        <button type="submit" class="btn btn-primary">Submit Payment</button>
                        <a href="{{ route('student.fees') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

@push('scripts')
<script>
document.getElementById('paymentMethod').addEventListener('change', function() {
    const gcashSection = document.getElementById('gcashSection');
    if (this.value === 'gcash') {
        gcashSection.style.display = 'block';
    } else {
        gcashSection.style.display = 'none';
    }
});
</script>
@endpush
@endsection
