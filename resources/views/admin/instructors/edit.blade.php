@extends('layouts.app')

@section('title', 'Edit Instructor')

@section('content')
<h2 class="mb-4"><i class="bi bi-person-gear me-2"></i>Edit instructor</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.instructors.update', $instructor) }}">
            @csrf
            @method('PUT')
            <div class="row g-3">
                <div class="col-md-6">
                    <label class="form-label">Name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $instructor->user->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Email</label>
                    <input type="email" name="email" class="form-control @error('email') is-invalid @enderror" value="{{ old('email', $instructor->user->email) }}" required>
                    @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Department</label>
                    <select name="department" class="form-select @error('department') is-invalid @enderror">
                        <option value="">-- None --</option>
                        <option value="7" {{ old('department', $instructor->department) === '7' ? 'selected' : '' }}>Grade 7</option>
                        <option value="8" {{ old('department', $instructor->department) === '8' ? 'selected' : '' }}>Grade 8</option>
                        <option value="9" {{ old('department', $instructor->department) === '9' ? 'selected' : '' }}>Grade 9</option>
                        <option value="10" {{ old('department', $instructor->department) === '10' ? 'selected' : '' }}>Grade 10</option>
                        <option value="11" {{ old('department', $instructor->department) === '11' ? 'selected' : '' }}>Grade 11</option>
                        <option value="12" {{ old('department', $instructor->department) === '12' ? 'selected' : '' }}>Grade 12</option>
                    </select>
                    @error('department')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Assign to courses</label>
                    <select name="course_ids[]" class="form-select @error('course_ids') is-invalid @enderror course-select" multiple size="10" id="courseSelectEdit" data-current-grade="{{ $instructor->department }}">
                        <optgroup label="Grade 7">
                            @foreach($courses->where('grade_level', '7') as $c)
                                <option value="{{ $c->id }}" data-grade="7" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Grade 8">
                            @foreach($courses->where('grade_level', '8') as $c)
                                <option value="{{ $c->id }}" data-grade="8" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Grade 9">
                            @foreach($courses->where('grade_level', '9') as $c)
                                <option value="{{ $c->id }}" data-grade="9" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Grade 10">
                            @foreach($courses->where('grade_level', '10') as $c)
                                <option value="{{ $c->id }}" data-grade="10" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Grade 11">
                            @foreach($courses->where('grade_level', '11') as $c)
                                <option value="{{ $c->id }}" data-grade="11" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="Grade 12">
                            @foreach($courses->where('grade_level', '12') as $c)
                                <option value="{{ $c->id }}" data-grade="12" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                        <optgroup label="No Grade Level">
                            @foreach($courses->whereNull('grade_level') as $c)
                                <option value="{{ $c->id }}" data-grade="" {{ in_array($c->id, old('course_ids', $instructor->courses()->pluck('id')->toArray())) ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                            @endforeach
                        </optgroup>
                    </select>
                    <small class="text-muted">Hold Ctrl/Cmd to select multiple. Courses are organized by grade level.</small>
                    @error('course_ids')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <button type="submit" class="btn btn-primary">Update instructor</button>
                    <a href="{{ route('admin.instructors.index') }}" class="btn btn-outline-secondary">Cancel</a>
                </div>
            </div>
        </form>
    </div>
</div>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const deptSelect = document.querySelector('select[name="department"]');
    const courseSelect = document.querySelector('#courseSelectEdit');

    if (deptSelect && courseSelect) {
        function filterCourses() {
            const selectedDept = deptSelect.value;
            const optgroups = courseSelect.querySelectorAll('optgroup');
            const options = courseSelect.querySelectorAll('option');

            optgroups.forEach(group => {
                group.style.display = 'none';
            });
            options.forEach(opt => {
                opt.style.display = 'none';
            });

            if (selectedDept) {
                // Show only the optgroup for the selected grade
                const targetOptgroup = Array.from(optgroups).find(og => 
                    og.label === `Grade ${selectedDept}`
                );
                if (targetOptgroup) {
                    targetOptgroup.style.display = 'block';
                    targetOptgroup.querySelectorAll('option').forEach(opt => {
                        opt.style.display = 'block';
                    });
                }
            } else {
                // Show all optgroups and options if no department selected
                optgroups.forEach(group => {
                    group.style.display = 'block';
                    group.querySelectorAll('option').forEach(opt => {
                        opt.style.display = 'block';
                    });
                });
            }
        }

        deptSelect.addEventListener('change', filterCourses);
        
        // Initialize on page load to match current selection
        filterCourses();
    }
});
</script>
@endsection
