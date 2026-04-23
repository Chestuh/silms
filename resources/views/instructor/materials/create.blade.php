@extends('layouts.app')

@section('title', isset($material) ? 'Edit Learning Material' : 'Add Learning Materials')

@section('content')
@php
    $selectedFormat = old('format', $material->format ?? 'document');
    $showFileInput = in_array($selectedFormat, ['document', 'pdf', 'video']);
    $showUrlInput = in_array($selectedFormat, ['link', 'quiz']);
@endphp
<h2 class="mb-4"><i class="bi bi-folder-plus me-2"></i>{{ isset($material) ? 'Edit learning material' : 'Add learning materials' }}</h2>
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
                <label class="form-label">Difficulty level</label>
                <select name="difficulty_level" class="form-select @error('difficulty_level') is-invalid @enderror">
                    <option value="easy" {{ old('difficulty_level', $material->difficulty_level ?? 'medium') === 'easy' ? 'selected' : '' }}>Easy</option>
                    <option value="medium" {{ old('difficulty_level', $material->difficulty_level ?? 'medium') === 'medium' ? 'selected' : '' }}>Medium</option>
                    <option value="hard" {{ old('difficulty_level', $material->difficulty_level ?? '') === 'hard' ? 'selected' : '' }}>Hard</option>
                </select>
                @error('difficulty_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>

            @if(isset($material))
            <div class="mb-3">
                <label class="form-label">Release date (optional)</label>
                <input type="datetime-local" name="release_date" class="form-control" value="{{ old('release_date', $material->release_date ? $material->release_date->format('Y-m-d\TH:i') : '') }}" placeholder="Leave empty for immediate release">
                <div class="form-text">Material will be available to students after this date</div>
            </div>
            @endif

            <hr class="my-4">
            <div class="d-flex justify-content-between align-items-center mb-3">
                <h5 class="mb-0">Materials</h5>
                <button type="button" class="btn btn-outline-primary btn-sm" id="add-material-btn">
                    <i class="bi bi-plus-lg me-1"></i>Add another material
                </button>
            </div>

            <div id="materials-container">
                <div class="material-item card mb-3 border">
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Title</label>
                                <input type="text" name="materials[0][title]" class="form-control" required>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Format</label>
                                <select name="materials[0][format]" class="form-select format-select">
                                    <option value="document">Document</option>
                                    <option value="pdf">PDF</option>
                                    <option value="video">Video</option>
                                    <option value="link">Link</option>
                                    <option value="quiz">Quiz</option>
                                </select>
                            </div>
                        </div>
                        <div class="mb-3">
                            <label class="form-label">Description</label>
                            <textarea name="materials[0][description]" class="form-control" rows="2"></textarea>
                        </div>
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label">Release date</label>
                                <input type="datetime-local" name="materials[0][release_date]" class="form-control" placeholder="Leave empty for immediate release">
                                <div class="form-text">Material will be available to students exact this date.</div>
                            </div>
                        </div>
                        <div class="file-input-group">
                            <label class="form-label">Choose file</label>
                            <input type="file" name="materials[0][file]" class="form-control" accept=".docx,.pdf,.mp4">
                            <div class="form-text">Supported file types: .docx, .pdf, .mp4</div>
                        </div>
                        <div class="url-input-group d-none">
                            <label class="form-label">URL</label>
                            <input type="url" name="materials[0][url]" class="form-control" placeholder="https://...">
                        </div>
                        <div class="text-end mt-2">
                            <button type="button" class="btn btn-outline-danger btn-sm remove-material d-none">Remove</button>
                        </div>
                    </div>
                </div>
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">{{ isset($material) ? 'Update material' : 'Add materials' }}</button>
                <a href="{{ route('instructor.materials.index') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
        @push('scripts')
            <script>
                let materialCount = 1;

                function updateMaterialFields(container) {
                    const format = container.querySelector('.format-select').value;
                    const fileGroup = container.querySelector('.file-input-group');
                    const urlGroup = container.querySelector('.url-input-group');

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

                document.getElementById('add-material-btn').addEventListener('click', function() {
                    const container = document.getElementById('materials-container');
                    const newItem = document.createElement('div');
                    newItem.className = 'material-item card mb-3 border';
                    newItem.innerHTML = `
                        <div class="card-body">
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Title</label>
                                    <input type="text" name="materials[${materialCount}][title]" class="form-control" required>
                                </div>
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Format</label>
                                    <select name="materials[${materialCount}][format]" class="form-select format-select">
                                        <option value="document">Document</option>
                                        <option value="pdf">PDF</option>
                                        <option value="video">Video</option>
                                        <option value="link">Link</option>
                                        <option value="quiz">Quiz</option>
                                    </select>
                                </div>
                            </div>
                            <div class="mb-3">
                                <label class="form-label">Description</label>
                                <textarea name="materials[${materialCount}][description]" class="form-control" rows="2"></textarea>
                            </div>
                            <div class="row">
                                <div class="col-md-6 mb-3">
                                    <label class="form-label">Release date (optional)</label>
                                    <input type="datetime-local" name="materials[${materialCount}][release_date]" class="form-control" placeholder="Leave empty for immediate release">
                                    <div class="form-text">Material will be available to students after this date</div>
                                </div>
                            </div>
                            <div class="file-input-group">
                                <label class="form-label">Choose file</label>
                                <input type="file" name="materials[${materialCount}][file]" class="form-control" accept=".docx,.pdf,.mp4">
                                <div class="form-text">Supported file types: .docx, .pdf, .mp4</div>
                            </div>
                            <div class="url-input-group d-none">
                                <label class="form-label">URL</label>
                                <input type="url" name="materials[${materialCount}][url]" class="form-control" placeholder="https://...">
                            </div>
                            <div class="text-end mt-2">
                                <button type="button" class="btn btn-outline-danger btn-sm remove-material">Remove</button>
                            </div>
                        </div>
                    `;
                    container.appendChild(newItem);
                    
                    newItem.querySelector('.format-select').addEventListener('change', function() {
                        updateMaterialFields(newItem);
                    });
                    
                    newItem.querySelector('.remove-material').addEventListener('click', function() {
                        newItem.remove();
                    });
                    
                    materialCount++;
                });

                document.querySelectorAll('.format-select').forEach(function(select) {
                    select.addEventListener('change', function() {
                        updateMaterialFields(this.closest('.material-item'));
                    });
                });

                document.querySelectorAll('.remove-material').forEach(function(btn) {
                    btn.addEventListener('click', function() {
                        this.closest('.material-item').remove();
                    });
                });
            </script>
        @endpush
    </div>
</div>
@endsection
