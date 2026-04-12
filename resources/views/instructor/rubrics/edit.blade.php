@extends('layouts.app')

@section('title', 'Edit rubric — ' . $rubric->name)

@section('content')
<h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit rubric</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('instructor.rubrics.update', $rubric) }}">
            @csrf
            @method('PUT')
            <div class="row gy-3">
                <div class="col-md-6">
                    <label class="form-label">Course</label>
                    <select id="course-select" name="course_id" class="form-select @error('course_id') is-invalid @enderror" required>
                        <option value="">Select course</option>
                        @foreach($courses as $course)
                            <option value="{{ $course->id }}" {{ old('course_id', $rubric->course_id) == $course->id ? 'selected' : '' }}>{{ $course->code }} — {{ $course->title }}</option>
                        @endforeach
                    </select>
                    @error('course_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-md-6">
                    <label class="form-label">Material (optional)</label>
                    <select id="material-select" name="material_id" class="form-select @error('material_id') is-invalid @enderror">
                        <option value="">Course-level rubric</option>
                        @foreach($materials as $material)
                            <option value="{{ $material->id }}" data-course-id="{{ $material->course_id }}" {{ old('material_id', $rubric->material_id) == $material->id ? 'selected' : '' }}>{{ $material->title }} ({{ $material->course->code ?? 'Unknown' }})</option>
                        @endforeach
                    </select>
                    @error('material_id')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
                <div class="col-12">
                    <label class="form-label">Rubric name</label>
                    <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $rubric->name) }}" required>
                    @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
                </div>
            </div>

            <div class="mt-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div>
                        <h5 class="mb-1">Rubric criteria</h5>
                        <p class="text-muted small mb-0">Update criterion details and weights below.</p>
                    </div>
                    <button type="button" id="add-criterion" class="btn btn-sm btn-outline-primary">Add criterion</button>
                </div>
                <div class="table-responsive">
                    <table class="table table-bordered align-middle mb-0" id="criteria-table">
                        <thead class="table-light">
                            <tr>
                                <th>Description</th>
                                <th style="width:140px;">Weight (%)</th>
                                <th style="width:60px;"></th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $criteriaOld = old('criteria', $rubric->criteria_json ?? []);
                            @endphp
                            @foreach($criteriaOld as $index => $item)
                                <tr>
                                    <td>
                                        <input type="text" name="criteria[{{ $index }}][description]" class="form-control" value="{{ $item['description'] ?? '' }}" required>
                                    </td>
                                    <td>
                                        <input type="number" min="0" max="100" step="1" name="criteria[{{ $index }}][weight]" class="form-control" value="{{ $item['weight'] ?? '' }}" required>
                                    </td>
                                    <td class="text-center">
                                        <button type="button" class="btn btn-sm btn-outline-danger remove-criterion">×</button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @error('criteria')<div class="text-danger small mt-2">{{ $message }}</div>@enderror
            </div>

            <div class="mt-4">
                <button type="submit" class="btn btn-success">Save changes</button>
                <a href="{{ route('instructor.rubrics.show', $rubric) }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>

<script>
    const materials = Array.from(document.querySelectorAll('#material-select option')).map(option => ({
        id: option.value,
        courseId: option.dataset.courseId,
        label: option.textContent,
    })).filter(item => item.id);

    const courseSelect = document.querySelector('#course-select');
    const materialSelect = document.querySelector('#material-select');

    function refreshMaterialOptions() {
        const selectedCourse = courseSelect.value;
        Array.from(materialSelect.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }
            option.hidden = selectedCourse && option.dataset.courseId !== selectedCourse;
        });
    }

    courseSelect?.addEventListener('change', refreshMaterialOptions);
    refreshMaterialOptions();

    function updateCriterionIndexes() {
        Array.from(document.querySelectorAll('#criteria-table tbody tr')).forEach((row, index) => {
            row.querySelectorAll('input').forEach(input => {
                const name = input.name.replace(/criteria\[\d+\]/, `criteria[${index}]`);
                input.name = name;
            });
        });
    }

    function addCriterionRow(description = '', weight = '') {
        const tbody = document.querySelector('#criteria-table tbody');
        const index = tbody.children.length;
        const row = document.createElement('tr');
        row.innerHTML = `
            <td><input type="text" name="criteria[${index}][description]" class="form-control" value="${description}" required></td>
            <td><input type="number" min="0" max="100" step="1" name="criteria[${index}][weight]" class="form-control" value="${weight}" required></td>
            <td class="text-center"><button type="button" class="btn btn-sm btn-outline-danger remove-criterion">×</button></td>
        `;
        tbody.appendChild(row);
    }

    document.querySelector('#add-criterion')?.addEventListener('click', () => addCriterionRow());

    document.querySelector('#criteria-table')?.addEventListener('click', event => {
        if (!event.target.closest('.remove-criterion')) {
            return;
        }
        const row = event.target.closest('tr');
        row.remove();
        updateCriterionIndexes();
    });
</script>
@endsection