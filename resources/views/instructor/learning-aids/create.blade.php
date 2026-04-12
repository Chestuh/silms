@extends('layouts.app')

@section('title', 'Schedule Auto Learning Aids')

@section('content')
<h2 class="mb-4"><i class="bi bi-cloud-upload me-2"></i>Upload Auto-Generated Learning Aids</h2>
<div class="card">
    <div class="card-body">
        <form action="{{ route('instructor.learning-aids.store') }}" method="POST" enctype="multipart/form-data">
            @csrf
            <div class="mb-3">
                <label class="form-label">Lesson / Material</label>
                <select name="material_id" class="form-select @error('material_id') is-invalid @enderror" required>
                    <option value="">Select lesson</option>
                    @foreach($materials as $material)
                    <option value="{{ $material->id }}" {{ old('material_id') == $material->id ? 'selected' : '' }}>{{ $material->title }} ({{ optional($material->course)->code ?? 'General' }})</option>
                    @endforeach
                </select>
                @error('material_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">File(s)</label>
                <input type="file" name="files[]" class="form-control @error('files.*') is-invalid @enderror" multiple required>
                @error('files.*')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <div class="mb-3">
                <label class="form-label">Release Date</label>
                <input type="datetime-local" name="release_at" class="form-control @error('release_at') is-invalid @enderror" value="{{ old('release_at') }}" required>
                @error('release_at')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            <button type="submit" class="btn btn-success">Schedule</button>
            <a href="{{ route('instructor.learning-aids.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@endsection
