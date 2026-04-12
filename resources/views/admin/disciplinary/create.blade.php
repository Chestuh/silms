@extends('layouts.app')

@section('title', 'Add Disciplinary Record')

@section('content')
<h2 class="mb-4"><i class="bi bi-shield-exclamation me-2"></i>Add Disciplinary Record</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.disciplinary.store') }}">
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
                <label class="form-label">Incident date</label>
                <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror" value="{{ old('incident_date', date('Y-m-d')) }}" required>
                @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Sanction</label>
                <input type="text" name="sanction" class="form-control @error('sanction') is-invalid @enderror" value="{{ old('sanction') }}" placeholder="e.g. Written warning, suspension">
                @error('sanction')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror">
                    <option value="pending" {{ old('status', 'pending') === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ old('status') === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="appealed" {{ old('status') === 'appealed' ? 'selected' : '' }}>Appealed</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Save</button>
            <a href="{{ route('admin.disciplinary.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.disciplinary.index') }}">&larr; Back to disciplinary records</a></p>
@endsection
