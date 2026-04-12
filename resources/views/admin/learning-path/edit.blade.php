@extends('layouts.app')

@section('title', 'Edit Learning Path Rule')

@section('content')
<h2 class="mb-4"><i class="bi bi-pencil-square me-2"></i>Edit learning path rule</h2>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('admin.learning-path.update', $rule) }}">
            @csrf
            @method('PUT')
            <div class="mb-3">
                <label class="form-label">Rule type</label>
                <select name="type" id="ruleType" class="form-select @error('type') is-invalid @enderror" required>
                    @foreach($typeOptions as $value => $label)
                        <option value="{{ $value }}" {{ old('type', $rule->type) === $value ? 'selected' : '' }}>{{ $label }}</option>
                    @endforeach
                </select>
                @error('type')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div class="mb-3">
                <label class="form-label">Name (optional)</label>
                <input type="text" name="name" class="form-control @error('name') is-invalid @enderror" value="{{ old('name', $rule->name) }}">
                @error('name')<div class="invalid-feedback">{{ $message }}</div>@enderror
            </div>
            <div id="coursePrereqFields" class="row g-2 mb-3 d-none">
                <div class="col-md-6">
                    <label class="form-label">Source course (complete first)</label>
                    <select name="source_course_id" class="form-select">
                        <option value="">— Select —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('source_course_id', $rule->source_course_id) == $c->id ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Target course (then)</label>
                    <select name="target_course_id" class="form-select">
                        <option value="">— Select —</option>
                        @foreach($courses as $c)
                            <option value="{{ $c->id }}" {{ old('target_course_id', $rule->target_course_id) == $c->id ? 'selected' : '' }}>{{ $c->code }} — {{ $c->title }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div id="materialPrereqFields" class="row g-2 mb-3 d-none">
                <div class="col-md-6">
                    <label class="form-label">Source material (complete first)</label>
                    <select name="source_material_id" class="form-select">
                        <option value="">— Select —</option>
                        @foreach($materials as $m)
                            <option value="{{ $m->id }}" {{ old('source_material_id', $rule->source_material_id) == $m->id ? 'selected' : '' }}>{{ $m->course->code ?? '—' }}: {{ Str::limit($m->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-6">
                    <label class="form-label">Target material (then)</label>
                    <select name="target_material_id" class="form-select">
                        <option value="">— Select —</option>
                        @foreach($materials as $m)
                            <option value="{{ $m->id }}" {{ old('target_material_id', $rule->target_material_id) == $m->id ? 'selected' : '' }}>{{ $m->course->code ?? '—' }}: {{ Str::limit($m->title, 40) }}</option>
                        @endforeach
                    </select>
                </div>
            </div>
            <div class="row g-2 mb-3">
                <div class="col-md-6">
                    <label class="form-label">Sort order</label>
                    <input type="number" name="sort_order" class="form-control" value="{{ old('sort_order', $rule->sort_order) }}" min="0">
                </div>
                <div class="col-md-6 d-flex align-items-end">
                    <div class="form-check">
                        <input type="hidden" name="is_active" value="0">
                        <input type="checkbox" name="is_active" value="1" class="form-check-input" id="is_active" {{ old('is_active', $rule->is_active) ? 'checked' : '' }}>
                        <label class="form-check-label" for="is_active">Active</label>
                    </div>
                </div>
            </div>
            <button type="submit" class="btn btn-primary">Update</button>
            <a href="{{ route('admin.learning-path.index') }}" class="btn btn-outline-secondary">Cancel</a>
        </form>
    </div>
</div>
@push('scripts')
<script>
document.getElementById('ruleType').addEventListener('change', function() {
    var v = this.value;
    document.getElementById('coursePrereqFields').classList.toggle('d-none', v !== 'course_prerequisite');
    document.getElementById('materialPrereqFields').classList.toggle('d-none', v !== 'material_prerequisite');
});
document.getElementById('ruleType').dispatchEvent(new Event('change'));
</script>
@endpush
<p class="small text-muted mt-2 mb-0"><a href="{{ route('admin.learning-path.index') }}">&larr; Back to learning path</a></p>
@endsection
