@extends('layouts.app')

@section('title', 'Edit student — ' . ($student->user->name ?? $student->student_number))

@section('content')
<h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit student</h2>

<form action="{{ route('admin.students.update', $student) }}" method="post">
    @csrf
    @method('put')
    <div class="row">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Account</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Name</label>
                        <input type="text" class="form-control @error('name') is-invalid @enderror" name="name" value="{{ old('name', $student->user->name ?? '') }}" required>
                        @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Email</label>
                        <input type="email" class="form-control @error('email') is-invalid @enderror" name="email" value="{{ old('email', $student->user->email ?? '') }}" required>
                        @error('email')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4">
                <div class="card-header bg-transparent fw-semibold">Student record</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Student number</label>
                        <input type="text" class="form-control @error('student_number') is-invalid @enderror" name="student_number" value="{{ old('student_number', $student->student_number ?? '') }}" disabled>
                        @error('student_number')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Grade</label>
                        @php
                            $yearLevel = old('year_level', $student->year_level ?? 0);
                            $gradeNumber = (int)$yearLevel + 6;
                        @endphp
                        <input type="text" class="form-control" value="Grade {{ $gradeNumber }}" readonly>
                        <input type="hidden" name="year_level" value="{{ $yearLevel }}">
                        @error('year_level')<div class="invalid-feedback" style="display: block;">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Semester</label>
                        <input type="text" class="form-control" id="semesterDisplay" value="1st Semester" readonly>
                        <input type="hidden" name="program" value="{{ old('program', $student->program ?? '') }}">
                        @error('program')<div class="invalid-feedback" style="display: block;">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Admission date</label>
                        <input type="date" class="form-control @error('admission_date') is-invalid @enderror" name="admission_date" value="{{ old('admission_date', $student->admission_date ? \Carbon\Carbon::parse($student->admission_date)->format('Y-m-d') : '') }}" disabled>
                        @error('admission_date')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Status</label>
                        <select class="form-select @error('status') is-invalid @enderror" name="status" disabled>
                            <option value="active" {{ old('status', $student->status) === 'active' ? 'selected' : '' }}>Active</option>
                            <option value="inactive" {{ old('status', $student->status) === 'inactive' ? 'selected' : '' }}>Inactive</option>
                            <option value="graduated" {{ old('status', $student->status) === 'graduated' ? 'selected' : '' }}>Graduated</option>
                            <option value="transferred" {{ old('status', $student->status) === 'transferred' ? 'selected' : '' }}>Transferred</option>
                        </select>
                        @error('status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                    <div class="mb-3">
                        <label class="form-label">Academic status</label>
                        <select class="form-select @error('academic_status') is-invalid @enderror" name="academic_status" disabled>
                            <option value="">Auto (from GWA)</option>
                            <option value="passed" {{ old('academic_status', $student->academic_status) === 'passed' ? 'selected' : '' }}>Passed</option>
                            <option value="failed" {{ old('academic_status', $student->academic_status) === 'failed' ? 'selected' : '' }}>Failed</option>
                            <option value="inc" {{ old('academic_status', $student->academic_status) === 'inc' ? 'selected' : '' }}>INC</option>
                            <option value="drop" {{ old('academic_status', $student->academic_status) === 'drop' ? 'selected' : '' }}>DROP</option>
                        </select>
                        @error('academic_status')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="mb-3">
        <button type="submit" class="btn btn-primary">Save changes</button>
        <a href="{{ route('admin.students.show', $student) }}" class="btn btn-outline-secondary">Cancel</a>
    </div>
</form>
<p class="small text-muted mb-0"><a href="{{ route('admin.students.index') }}">&larr; Back to students</a></p>

<script>
document.addEventListener('DOMContentLoaded', function() {
    const admissionDateInput = document.querySelector('input[name="admission_date"]');
    const semesterDisplay = document.getElementById('semesterDisplay');
    
    function updateSemester() {
        if (admissionDateInput && admissionDateInput.value) {
            const date = new Date(admissionDateInput.value);
            const month = date.getMonth() + 1; // getMonth() returns 0-11
            
            // Determine semester: 1st sem (Jan-Jun), 2nd sem (Jul-Dec)
            const semester = month <= 6 ? '1st Semester' : '2nd Semester';
            semesterDisplay.value = semester;
        }
    }
    
    // Initial update
    updateSemester();
    
    // Update when admission date changes
    if (admissionDateInput) {
        admissionDateInput.addEventListener('change', updateSemester);
    }
});
</script>
@endsection
