@extends('layouts.app')

@section('title', 'Add Admission Record')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-plus me-2"></i>Add Admission Record</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.admission.store') }}">
            @csrf
            <div class="mb-3">
                <label class="form-label">Student</label>
                <select name="student_id" class="form-select @error('student_id') is-invalid @enderror" required>
                    <option value="">Select student</option>
                    @foreach($students as $s)
                        <option value="{{ $s->id }}" {{ old('student_id') == $s->id ? 'selected' : '' }}>{{ $s->student_number }} — {{ $s->user->name ?? '—' }}</option>
                    @endforeach
                </select>
                @error('student_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Record type</label>
                <select name="record_type" class="form-select @error('record_type') is-invalid @enderror" required>
                    <option value="admission" {{ old('record_type') === 'admission' ? 'selected' : '' }}>Admission</option>
                    <option value="transfer" {{ old('record_type') === 'transfer' ? 'selected' : '' }}>Transfer (in/out)</option>
                    <option value="readmission" {{ old('record_type') === 'readmission' ? 'selected' : '' }}>Re-admission</option>
                    <option value="leave" {{ old('record_type') === 'leave' ? 'selected' : '' }}>Leave</option>
                </select>
                @error('record_type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Date processed</label>
                <input type="date" name="date_processed" class="form-control @error('date_processed') is-invalid @enderror" value="{{ old('date_processed', date('Y-m-d')) }}">
                @error('date_processed')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Notes</label>
                <textarea name="notes" class="form-control @error('notes') is-invalid @enderror" rows="3">{{ old('notes') }}</textarea>
                @error('notes')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-success">Save</button>
            <a href="{{ route('admin.admission.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.admission.index') }}">&larr; Back to admission records</a></p>
@endsection
