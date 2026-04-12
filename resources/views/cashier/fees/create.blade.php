@extends('layouts.app')

@section('title', 'Add payment to all students')

@section('content')
<h2 class="mb-4"><i class="bi bi-plus-circle me-2"></i>Add payment to all students</h2>
<p class="text-muted small mb-3">Create a fee that will be added to every <strong>active enrolled</strong> student. Each student will see this fee on their Fees & Payments page.</p>

<div class="row">
    <div class="col-lg-8">
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-transparent fw-semibold">New fee (applies to all enrolled students)</div>
            <div class="card-body">
                <div class="alert alert-info mb-4">
                    <i class="bi bi-people me-2"></i>
                    <strong>{{ $enrolledCount }}</strong> active student(s) will receive this fee.
                </div>

                <form action="{{ route('cashier.fees.store') }}" method="post">
                    @csrf
                    <div class="mb-3">
                        <label class="form-label">Fee type <span class="text-danger">*</span></label>
                        <input type="text" class="form-control @error('fee_type') is-invalid @enderror" name="fee_type" value="{{ old('fee_type') }}" placeholder="e.g. Tuition Fee - 1st Sem, Miscellaneous Fee" required>
                        @error('fee_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Amount (₱) <span class="text-danger">*</span></label>
                        <input type="number" step="0.01" min="0" class="form-control @error('amount') is-invalid @enderror" name="amount" value="{{ old('amount') }}" required>
                        @error('amount')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Due date (optional)</label>
                        <input type="date" class="form-control @error('due_date') is-invalid @enderror" name="due_date" value="{{ old('due_date') }}">
                        @error('due_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="d-flex gap-2">
                        <button type="submit" class="btn btn-primary">Add fee to all students</button>
                        <a href="{{ route('cashier.fees.index') }}" class="btn btn-outline-secondary">Cancel</a>
                    </div>
                </form>
            </div>
        </div>
    </div>
</div>

<p class="small text-muted mb-0"><a href="{{ route('cashier.fees.index') }}">&larr; Back to fees</a></p>
@endsection
