@extends('layouts.app')

@section('title', 'Edit Course')

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-richtext me-2"></i>Edit course</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.courses.update', $course) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Code</label>
                <input type="text" name="code" class="form-control @error('code') is-invalid @enderror" value="{{ old('code', $course->code) }}" required>
                @error('code')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Title</label>
                <input type="text" name="title" class="form-control @error('title') is-invalid @enderror" value="{{ old('title', $course->title) }}" required>
                @error('title')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Department</label>
                <select name="grade_level" id="grade_level" class="form-select @error('grade_level') is-invalid @enderror">
                    <option value="">-- None --</option>
                    <option value="7" {{ old('grade_level', $course->grade_level) === '7' ? 'selected' : '' }}>Grade 7</option>
                    <option value="8" {{ old('grade_level', $course->grade_level) === '8' ? 'selected' : '' }}>Grade 8</option>
                    <option value="9" {{ old('grade_level', $course->grade_level) === '9' ? 'selected' : '' }}>Grade 9</option>
                    <option value="10" {{ old('grade_level', $course->grade_level) === '10' ? 'selected' : '' }}>Grade 10</option>
                    <option value="11" {{ old('grade_level', $course->grade_level) === '11' ? 'selected' : '' }}>Grade 11</option>
                    <option value="12" {{ old('grade_level', $course->grade_level) === '12' ? 'selected' : '' }}>Grade 12</option>
                </select>
                @error('grade_level')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3" id="units-group" style="display: none;">
                <label class="form-label">Units</label>
                <input type="number" name="units" id="units" class="form-control @error('units') is-invalid @enderror" value="{{ old('units', $course->units ?? 3) }}" min="1" max="10" step="1">
                @error('units')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3" id="semester-group" style="display: none;">
                <label class="form-label">Semester</label>
                <select name="semester" id="semester" class="form-select @error('semester') is-invalid @enderror">
                    <option value="1st Semester" {{ old('semester', $course->semester) === '1st Semester' ? 'selected' : '' }}>1st Semester</option>
                    <option value="2nd Semester" {{ old('semester', $course->semester) === '2nd Semester' ? 'selected' : '' }}>2nd Semester</option>
                </select>
                @error('semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <button type="submit" class="btn btn-primary">Update course</button>
            <a href="{{ route('admin.courses.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
        <script>
        document.addEventListener('DOMContentLoaded', function() {
            var gradeLevel = document.getElementById('grade_level');
            var unitsGroup = document.getElementById('units-group');
            var semesterGroup = document.getElementById('semester-group');
            function toggleFields() {
                if (gradeLevel.value === '11' || gradeLevel.value === '12') {
                    unitsGroup.style.display = '';
                    semesterGroup.style.display = '';
                } else {
                    unitsGroup.style.display = 'none';
                    semesterGroup.style.display = 'none';
                }
            }
            gradeLevel.addEventListener('change', toggleFields);
            toggleFields();
        });
        </script>
    </div>
</div>
@endsection
