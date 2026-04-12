@extends('layouts.app')

@section('title', isset($material) ? 'Edit Learning Material' : 'Add Learning Material')

@section('content')
<h2 class="mb-4"><i class="bi bi-folder-plus me-2"></i>{{ isset($material) ? 'Edit learning material' : 'Add learning material' }}</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ isset($material) ? route('instructor.materials.update', $material) : route('instructor.materials.store') }}" enctype="multipart/form-data">
            @csrf
            @if(isset($material))
                @method('PUT')
            @endif
            <div class="mb-3">
                <label class="form-label">Course</label>
                <select name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                    <option value="">Select course</option>
                    @foreach($courses as $c)
                        <option value="{{ $c->id }}" {{ (string) old('course_id', $material->course_id ?? '') === (string) $c->id ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                    @endforeach
                </select>
                @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $material->title ?? '') }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Description</label>
                <textarea name="description" class="form-control @error('description') is-invalid @enderror" rows="3">{{ old('description', $material->description ?? '') }}</textarea>
                @error('description')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Format</label>
                <select id="format" name="format" class="form-select @error('format') is-invalid @enderror">
                    <option value="document" {{ old('format', $material->format ?? 'document') === 'document' ? 'selected' : '' }}>Document</option>
                    <option value="pdf" {{ old('format', $material->format ?? '') === 'pdf' ? 'selected' : '' }}>PDF</option>
                    <option value="video" {{ old('format', $material->format ?? '') === 'video' ? 'selected' : '' }}>Video</option>
                    <option value="link" {{ old('format', $material->format ?? '') === 'link' ? 'selected' : '' }}>Link</option>
                    <option value="quiz" {{ old('format', $material->format ?? '') === 'quiz' ? 'selected' : '' }}>Quiz</option>
                </select>
                @error('format')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div id="file-input-group" class="mb-3 d-none">
                <label class="form-label">Choose file</label>
                <input type="file" name="file" class="form-control @error('file') is-invalid @enderror" accept=".docx,.pdf,.mp4">
                @error('file')<div class="invalid-feedback">{{ $message }}</div>@enderror
                @if(isset($material) && $material->file_path)
                    <div class="mt-2">
                        <small>Current file: <a href="{{ asset('uploads/' . $material->file_path) }}" target="_blank">{{ basename($material->file_path) }}</a></small>
                    </div>
                @endif
                <div class="form-text">Supported file types: .docx, .pdf, .mp4</div>
            </div>
            <div id="url-input-group" class="mb-3 d-none">
                <label class="form-label">URL</label>
                <input id="url-input" type="url" name="url" class="form-control @error('url') is-invalid @enderror" value="{{ old('url', $material->url ?? '') }}" placeholder="https://...">
                @error('url')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Difficulty level</label>
                <select name="difficulty_level" class="form-select @error('difficulty_level') is-invalid @enderror">
                    <option value="easy" {{ old('difficulty_level', $material->difficulty_level ?? 'medium') === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty_level', $material->difficulty_level ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ old('difficulty_level', $material->difficulty_level ?? '') === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
                @error('difficulty_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-success">{{ isset($material) ? 'Update material' : 'Add material' }}</button>
            <a href="{{ route('instructor.materials.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
        @push('scripts')
            <script>
                function updateMaterialFields() {
                    const format = document.getElementById('format').value;
                    const fileGroup = document.getElementById('file-input-group');
                    const urlGroup = document.getElementById('url-input-group');

                    if (format === 'document' || format === 'pdf' || format === 'video') {
                        fileGroup.classList.remove('d-none');
                        urlGroup.classList.add('d-none');
                    } else if (format === 'quiz' || format === 'link') {
                        fileGroup.classList.add('d-none');
                        urlGroup.classList.remove('d-none');
                    } else {
                        fileGroup.classList.add('d-none');
                        urlGroup.classList.add('d-none');
                    }
                }

                document.addEventListener('DOMContentLoaded', function () {
                    const formatSelect = document.getElementById('format');
                    formatSelect.addEventListener('change', updateMaterialFields);
                    updateMaterialFields();
                });
            </script>
        @endpush
    </div>
</div>
@endsection
