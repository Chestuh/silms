@extends('layouts.app')

@section('title', 'Customization & Personalization')

@section('content')
<h2 class="mb-4"><i class="bi bi-gear me-2"></i>Customization &amp; personalization</h2>
<p class="text-muted small mb-3">Configure the defaults that guide student dashboard preferences, layout, and learning focus.</p>

<form action="{{ route('admin.settings.update') }}" method="post">
    @csrf
    @method('put')

    <div class="row g-4">
        <div class="col-lg-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold">Student personalization</div>
                <div class="card-body">
                    <div class="form-check form-switch mb-4">
                        <input class="form-check-input" type="hidden" name="allow_student_personalization" value="0">
                        <input class="form-check-input" type="checkbox" id="allowPersonalization" name="allow_student_personalization" value="1" {{ old('allow_student_personalization', $allowPersonalization) ? 'checked' : '' }}>
                        <label class="form-check-label" for="allowPersonalization">Allow students to personalize their dashboard</label>
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default dashboard layout</label>
                        <select class="form-select @error('default_dashboard_layout') is-invalid @enderror" name="default_dashboard_layout" required>
                            <option value="balanced" {{ old('default_dashboard_layout', $dashboardLayout) === 'balanced' ? 'selected' : '' }}>Balanced layout</option>
                            <option value="compact" {{ old('default_dashboard_layout', $dashboardLayout) === 'compact' ? 'selected' : '' }}>Compact layout</option>
                            <option value="spacious" {{ old('default_dashboard_layout', $dashboardLayout) === 'spacious' ? 'selected' : '' }}>Spacious layout</option>
                        </select>
                        <div class="form-text">Choose the default widget density for student dashboards.</div>
                        @error('default_dashboard_layout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>

                    <div class="mb-3">
                        <label class="form-label">Default learning focus</label>
                        <select class="form-select @error('default_learning_focus') is-invalid @enderror" name="default_learning_focus" required>
                            <option value="academic_progress" {{ old('default_learning_focus', $learningFocus) === 'academic_progress' ? 'selected' : '' }}>Academic progress</option>
                            <option value="learning_materials" {{ old('default_learning_focus', $learningFocus) === 'learning_materials' ? 'selected' : '' }}>Learning materials</option>
                            <option value="assessment_readiness" {{ old('default_learning_focus', $learningFocus) === 'assessment_readiness' ? 'selected' : '' }}>Assessment readiness</option>
                            <option value="self_assessment" {{ old('default_learning_focus', $learningFocus) === 'self_assessment' ? 'selected' : '' }}>Self-assessment</option>
                        </select>
                        <div class="form-text">This sets the recommended focus shown to students when they first open their dashboard.</div>
                        @error('default_learning_focus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>
        </div>

        <div class="col-lg-6">
            <div class="card border-0 shadow-sm mb-4 h-100">
                <div class="card-header bg-transparent fw-semibold">Academic defaults</div>
                <div class="card-body">
                    <div class="mb-3">
                        <label class="form-label">Max units per semester</label>
                        <input type="number" min="1" max="99" class="form-control @error('max_units_per_semester') is-invalid @enderror" name="max_units_per_semester" value="{{ old('max_units_per_semester', $maxUnits) }}" required>
                        <div class="form-text">Used for academic load validation on the student dashboard.</div>
                        @error('max_units_per_semester')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    </div>
                </div>
            </div>

            {{-- <div class="card border-0 shadow-sm h-100">
                <div class="card-header bg-transparent fw-semibold">Application info</div>
                <div class="card-body">
                    <p class="small text-muted mb-2">Application name and tagline are managed from <code>config/app.php</code> and <code>.env</code>.</p>
                    <dl class="row small mb-0">
                        <dt class="col-sm-4 text-muted">App name</dt><dd class="col-sm-8">{{ $appName }}</dd>
                        <dt class="col-sm-4 text-muted">Tagline</dt><dd class="col-sm-8">{{ $tagline ?: '—' }}</dd>
                    </dl>
                </div>
            </div> --}}
        </div>
    </div>

    <div class="mt-4">
        <button type="submit" class="btn btn-primary">Save personalization defaults</button>
        <a href="{{ route('admin.dashboard') }}" class="btn btn-link text-decoration-none">&larr; Back to dashboard</a>
    </div>
</form>
@endsection
