@extends('layouts.app')

@section('title', 'Request Transfer')

@section('content')
<div class="container">
    <div class="d-flex align-items-center justify-content-between mb-4">
        <h2>Request Transfer</h2>
        <a href="{{ route('student.transfer-requests.index') }}" class="btn btn-secondary">Back to Requests</a>
    </div>

    <div class="card p-4">
        <form method="POST" action="{{ route('student.transfer-requests.store') }}">
            @csrf

            <div class="mb-3">
                <label for="from_school" class="form-label">Current School</label>
                <input id="from_school" name="from_school" type="text" class="form-control @error('from_school') is-invalid @enderror" value="{{ old('from_school') }}" required>
                @error('from_school')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="to_school" class="form-label">Requested School</label>
                <input id="to_school" name="to_school" type="text" class="form-control @error('to_school') is-invalid @enderror" value="{{ old('to_school') }}" required>
                @error('to_school')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <div class="mb-3">
                <label for="notes" class="form-label">Reason / Notes</label>
                <textarea id="notes" name="notes" rows="4" class="form-control @error('notes') is-invalid @enderror">{{ old('notes') }}</textarea>
                @error('notes')
                    <div class="invalid-feedback">{{ $message }}</div>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Submit Request</button>
        </form>
    </div>
</div>
@endsection
