@extends('layouts.app')

@section('title', 'Encode grades — ' . $course->code)

@section('content')
<h2 class="mb-4"><i class="bi bi-journal-check me-2"></i>{{ $course->code }} — {{ $course->title }}</h2>
<p class="text-muted small mb-3">Midterm and final grades (0–100). GWA is computed from (midterm + final) / 2 per subject.</p>

<form action="{{ route('instructor.grades.update', $course) }}" method="post">
    @csrf
    @method('put')
    <div class="card border-0 shadow-sm">
        <div class="card-body">
            <div class="row gy-3 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Apply rubric</label>
                    <select name="rubric_id" class="form-select @error('rubric_id') is-invalid @enderror">
                        <option value="">No rubric selected</option>
                        @foreach($rubrics as $rubric)
                            <option value="{{ $rubric->id }}" {{ (int) old('rubric_id', $selectedRubricId) === $rubric->id ? 'selected' : '' }}>{{ $rubric->name }} ({{ $rubric->material ? $rubric->material->title : 'Course rubric' }})</option>
                        @endforeach
                    </select>
                    @error('rubric_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <div class="form-text text-muted">Selected rubric will be attached to submitted grade records for this course.</div>
                </div>
            </div>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover align-middle mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>Student No.</th>
                            <th>Name</th>
                            <th>Rubric</th>
                            <th>Midterm</th>
                            <th>Final</th>
                            <th>Computed avg</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($enrollments as $e)
                            @php
                                $g = $e->grade;
                                $mid = $g ? $g->midterm_grade : null;
                                $fin = $g ? $g->final_grade : null;
                                $avg = ($mid !== null && $fin !== null) ? (floatval($mid) + floatval($fin)) / 2 : null;
                            @endphp
                            <tr>
                                <td>{{ $e->student->student_number ?? '—' }}</td>
                                <td class="fw-medium">{{ $e->student->user->name ?? '—' }}</td>
                                <td>{{ optional($g->rubric)->name ?? '—' }}</td>
                                <td style="width:120px">
                                    <input type="hidden" name="grades[{{ $loop->index }}][enrollment_id]" value="{{ $e->id }}">
                                    <input type="number" step="1" min="0" max="100" class="form-control form-control-sm" name="grades[{{ $loop->index }}][midterm_grade]" value="{{ $mid !== null ? intval($mid) : '' }}" placeholder="—">
                                </td>
                                <td style="width:120px">
                                    <input type="number" step="1" min="0" max="100" class="form-control form-control-sm" name="grades[{{ $loop->index }}][final_grade]" value="{{ $fin !== null ? intval($fin) : '' }}" placeholder="—">
                                </td>
                                <td class="text-muted small">{{ $avg !== null ? number_format($avg, 2) : '—' }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="5" class="text-center text-muted py-4">No enrolled students.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
        @if($enrollments->isNotEmpty())
            <div class="card-footer">
                <button type="submit" class="btn btn-primary">Save grades</button>
            </div>
        @endif
    </div>
</form>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('instructor.grades.index') }}">&larr; Back to grades</a></p>
@endsection
