@extends('layouts.app')

@section('title', 'Customize Dashboard')

@section('content')
<h2 class="mb-4"><i class="bi bi-sliders me-2"></i>Customize Dashboard</h2>
<p class="text-muted small mb-3">Personalize your dashboard layout, preferences, and learning focus.</p>
<div class="card border-0 shadow-sm">
    <div class="card-body">
        <form method="POST" action="{{ route('student.dashboard-personalization.update') }}">
            @csrf
            @method('PUT')
            <div class="row gy-4">
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Layout</label>
                    <select name="layout" class="form-select @error('layout') is-invalid @enderror">
                        <option value="default" {{ old('layout', optional($pref)->layout ?? 'default') === 'default' ? 'selected' : '' }}>Default</option>
                        <option value="compact" {{ old('layout', optional($pref)->layout ?? '') === 'compact' ? 'selected' : '' }}>Compact</option>
                        <option value="focus" {{ old('layout', optional($pref)->layout ?? '') === 'focus' ? 'selected' : '' }}>Focus (minimal)</option>
                    </select>
                    @error('layout')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <p class="small text-muted mt-1 mb-0">Controls how much information is shown on your dashboard.</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Theme color</label>
                    <input type="color" name="theme_color" class="form-control form-control-color @error('theme_color') is-invalid @enderror p-1" value="{{ old('theme_color', optional($pref)->theme_color ?? '#0d6efd') }}" title="Choose your dashboard accent color">
                    @error('theme_color')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <p class="small text-muted mt-1 mb-0">Pick an accent color for your dashboard highlights and cards.</p>
                </div>
                <div class="col-md-6">
                    <label class="form-label fw-semibold">Learning focus</label>
                    <input type="text" name="learning_focus" class="form-control @error('learning_focus') is-invalid @enderror" value="{{ old('learning_focus', optional($pref)->learning_focus ?? '') }}" placeholder="e.g. CS 101, or a short goal">
                    @error('learning_focus')<div class="invalid-feedback">{{ $message }}</div>@enderror
                    <p class="small text-muted mt-1 mb-0">Optional: a course code or goal to keep in mind (displayed on dashboard).</p>
                </div>
                <div class="col-12">
                    <label class="form-label fw-semibold">Dashboard widgets</label>
                    <div class="table-responsive">
                        <table class="table table-borderless align-middle mb-0">
                            <thead>
                                <tr>
                                    <th class="small text-muted">Show</th>
                                    <th class="small text-muted">Widget</th>
                                    <th class="small text-muted">Order</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach($widgetOptions as $widgetKey => $widgetLabel)
                                    @php
                                        $widgetEnabled = old("widgets.$widgetKey.enabled", data_get(optional($pref)->widgets, "$widgetKey.enabled", true));
                                        $widgetOrder = old("widgets.$widgetKey.order", data_get(optional($pref)->widgets, "$widgetKey.order", $loop->iteration));
                                    @endphp
                                    <tr>
                                        <td>
                                            <input type="hidden" name="widgets[{{ $widgetKey }}][enabled]" value="0">
                                            <input class="form-check-input" type="checkbox" id="widget_{{ $widgetKey }}" name="widgets[{{ $widgetKey }}][enabled]" value="1" {{ $widgetEnabled ? 'checked' : '' }}>
                                        </td>
                                        <td>
                                            <label class="form-check-label" for="widget_{{ $widgetKey }}">{{ $widgetLabel }}</label>
                                        </td>
                                        <td>
                                            <select name="widgets[{{ $widgetKey }}][order]" class="form-select form-select-sm" style="width: 100px;">
                                                @for($i = 1; $i <= count($widgetOptions); $i++)
                                                    <option value="{{ $i }}" {{ $widgetOrder == $i ? 'selected' : '' }}>{{ $i }}</option>
                                                @endfor
                                            </select>
                                        </td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    <p class="small text-muted mt-1 mb-0">Choose which KPI cards appear on your dashboard and the order they display.</p>
                </div>
            </div>
            <div class="mt-4">
                <button type="submit" class="btn btn-primary">Save preferences</button>
                <a href="{{ route('student.dashboard') }}" class="btn btn-outline-secondary">Cancel</a>
            </div>
        </form>
    </div>
</div>
<p class="small text-muted mt-2 mb-0"><a href="{{ route('student.dashboard') }}">&larr; Back to dashboard</a></p>
@endsection
