@extends('layouts.app')

@section('title', 'Edit Disciplinary Record')

@section('content')
<h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit Disciplinary Record</h2>
<p class="text-muted small mb-3">Student: {{ $disciplinary->student->user->name ?? '—' }} ({{ $disciplinary->student->student_number }})</p>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.disciplinary.update', $disciplinary) }}">
            @csrf
            @method('put')
            <div class="mb-3">
                <label class="form-label">Incident date</label>
                <input type="date" name="incident_date" class="form-control @error('incident_date') is-invalid @enderror" value="{{ old('incident_date', ($disciplinary->incident_date ?? null) ? \Carbon\Carbon::parse($disciplinary->incident_date)->format('Y-m-d') : '') }}" required>
                @error('incident_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="4" required>{{ old('description', $disciplinary->description) }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Sanction</label>
                <input type="text" name="sanction" class="form-control @error('sanction') is-invalid @enderror" value="{{ old('sanction', $disciplinary->sanction) }}">
                @error('sanction')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Status</label>
                <select name="status" class="form-select @error('status') is-invalid @enderror" required>
                    <option value="pending" {{ old('status', $disciplinary->status) === 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="resolved" {{ old('status', $disciplinary->status) === 'resolved' ? 'selected' : '' }}>Resolved</option>
                    <option value="appealed" {{ old('status', $disciplinary->status) === 'appealed' ? 'selected' : '' }}>Appealed</option>
                </select>
                @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.disciplinary.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.disciplinary.index') }}">&larr; Back to disciplinary records</a></p>
@endsection
